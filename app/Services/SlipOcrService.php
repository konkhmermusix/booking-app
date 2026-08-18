<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SlipOcrService
{
    /**
     * Verify payment slip image against expected total amount.
     *
     * @param string $filePath Full path to slip image file
     * @param float $expectedUsd Expected amount in USD
     * @return array
     */
    public function verifySlip(string $filePath, float $expectedUsd): array
    {
        // ទាញ KHR Exchange Rate
        $khrExchangeRate = floatval(DB::table('contact_settings')->where('key', 'khr_rate')->value('value') ?: 4050);
        $expectedKhr = round($expectedUsd * $khrExchangeRate);

        // ទាញ Account Name ដែលអតិថិជនត្រូវតែប្រើ (Fallback: LEAV SIS)
        $expectedAccountName = strtoupper(trim(DB::table('contact_settings')->where('key', 'bank_account_name')->value('value') ?: 'LEAV SIS'));

        // ព្យាយាមប្រើប្រាស់ Google Gemini Vision AI ជាចម្បង
        $geminiResult = $this->verifyWithGeminiVision($filePath, $expectedUsd, $expectedKhr, $khrExchangeRate, $expectedAccountName);
        if ($geminiResult !== null) {
            return $geminiResult;
        }

        // បម្រុងទុក ប្រើប្រាស់ Local Tesseract & Free OCR API Inspection
        $extractedText = $this->extractTextFromImage($filePath);
        $extractedText = $this->convertKhmerDigits($extractedText);

        if ($this->isQrCardOrFakeImage($extractedText, $filePath)) {
            return [
                'status'          => 'fake',
                'badge'           => 'រូបភាពបង្កាន់ដៃបង់ប្រាក់ក្លែងក្លាយ',
                'message'         => "រូបភាពដែលលោកអ្នកបានអាប់ឡូតមិនមែនជា «បង្កាន់ដៃទូទាត់ប្រាក់ធនាគារ» ពិតប្រាកដឡើយ។ សូមអាប់ឡូតរូបបង្កាន់ដៃដែលទទួលបានពី Mobile Banking App (ABA, ACLEDA, Wing, Bakong...)",
                'detected_amount' => null,
                'expected_usd'    => $expectedUsd,
                'expected_khr'    => $expectedKhr,
            ];
        }

        // ពិនិត្យឈ្មោះគណនីអ្នកទទួលលុយ (LEAV SIS) ក្នុង Local OCR Fallback
        if (!empty($expectedAccountName)) {
            $upperText = strtoupper($extractedText);
            similar_text($upperText, $expectedAccountName, $pct);
            $hasAccountName = str_contains($upperText, $expectedAccountName) || $pct >= 60;
            if (!$hasAccountName) {
                return [
                    'status'          => 'wrong_account',
                    'badge'           => 'គណនីមិនត្រឹមត្រូវ',
                    'message'         => "បង្កាន់ដៃនេះមិនបានផ្ទេរប្រាក់មកកាន់ «<b>{$expectedAccountName}</b>» ទេ។ សូមពិនិត្យ និងស្កែនឃ្យូអរកូដឡើងវិញដើម្បីផ្ទេរប្រាក់ទៅកាន់ ({$expectedAccountName})!",
                    'detected_amount' => null,
                    'expected_usd'    => $expectedUsd,
                    'expected_khr'    => $expectedKhr,
                ];
            }
        }

        $amounts = $this->findAmountsInText($extractedText);

        $matchedUsd = null;
        $isExactMatch = false;

        foreach ($amounts as $amt) {
            if (abs($amt - $expectedUsd) < 0.05) {
                $matchedUsd = $amt;
                $isExactMatch = true;
                break;
            }

            if (abs($amt - $expectedKhr) < 1000) {
                $matchedUsd = round($amt / $khrExchangeRate, 2);
                $isExactMatch = true;
                break;
            }
        }

        if ($isExactMatch) {
            return [
                'status'          => 'exact',
                'badge'           => 'ផ្ទៀងផ្ទាត់ជោគជ័យ',
                'message'         => "បានបង់ប្រាក់គ្រប់ចំនួនមិនខ្វះមិនលើស! (\${$expectedUsd} / " . number_format($expectedKhr) . " ៛)",
                'detected_amount' => $matchedUsd,
                'expected_usd'    => $expectedUsd,
                'expected_khr'    => $expectedKhr,
            ];
        }

        if (!empty($amounts)) {
            $detectedUsdVal = $amounts[0] > 1000 ? round($amounts[0] / $khrExchangeRate, 2) : $amounts[0];
            return [
                'status'          => 'mismatch',
                'badge'           => 'ចំនួនទឹកប្រាក់មិនត្រូវគ្នា',
                'message'         => "រកឃើញចំនួនបង់ប្រាក់ត្រឹមតែ (\${$detectedUsdVal}) ប៉ុន្តែប្រព័ន្ធតម្រូវឱ្យបង់ (\${$expectedUsd})! សូមពិនិត្យឡើងវិញ។",
                'detected_amount' => $detectedUsdVal,
                'expected_usd'    => $expectedUsd,
                'expected_khr'    => $expectedKhr,
            ];
        }

        return [
            'status'          => 'manual',
            'badge'           => 'មិនអាចអានចំនួនទឹកប្រាក់បានច្បាស់',
            'message'         => "មិនអាចអានចំនួនទឹកប្រាក់របស់អ្នកបានឡើយ។ សូមអាប់ឡូតរូបភាពបង្កាន់ដៃបង់ប្រាក់ឱ្យបានគ្រប់ចំនួន (\${$expectedUsd} / " . number_format($expectedKhr) . " ៛)។",
            'detected_amount' => null,
            'expected_usd'    => $expectedUsd,
            'expected_khr'    => $expectedKhr,
        ];
    }

    /**
     * Google Gemini Vision API Inspector (verifies TrueMoney, ACLEDA, ABA, Wing, Bakong bank slips accurately)
     */
    protected function verifyWithGeminiVision(string $filePath, float $expectedUsd, float $expectedKhr, float $khrRate = 4050, string $expectedAccountName = ''): ?array
    {
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey || !file_exists($filePath)) {
            return null;
        }

        $imageBytes = file_get_contents($filePath);
        $base64Image = base64_encode($imageBytes);

        $mimeType = 'image/jpeg';
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($ext === 'png') {
            $mimeType = 'image/png';
        } elseif ($ext === 'webp') {
            $mimeType = 'image/webp';
        }

        $prompt = "You are an expert fraud detection AI specializing in Cambodian mobile bank payment receipts (ACLEDA Bank, ABA Mobile, Wing Money, Bakong, TrueMoney, etc.). "
            . "RULE 1 — Amount & Currency (GENERIC ANY-AMOUNT PATTERN MATCHING): Extract whatever actual transfer amount appears on the receipt slip. "
            . "The transfer amount can be any number (e.g., 5.00, 12.50, 35.00, 80.00, 150.00, 250.00, etc.) formatted in any of these ways: "
            . "A. USD Formats (Extract as USD currency): "
            . "   - Prefix Symbol/Code: '\${amount}', '\$ {amount}', 'USD {amount}', 'USD{amount}' "
            . "   - Suffix Symbol/Code: '{amount}\$', '{amount} USD', '{amount}USD' "
            . "   - Khmer Text Currency: '{amount} ដុល្លារ', '{amount} ដុល្លារអាមេរិក' "
            . "B. KHR Formats (Extract as KHR currency): "
            . "   - Prefix Symbol/Code: '៛ {amount}', '៛{amount}', 'KHR {amount}', 'KHR{amount}' "
            . "   - Suffix Symbol/Code: '{amount} ៛', '{amount}៛', '{amount} KHR', '{amount}KHR' "
            . "   - Khmer Text Currency: '{amount} រៀល', '{amount}រៀល' "
            . "C. Digit & Punctuation Rules: "
            . "   - Convert any Khmer digits (០=0, ១=1, ២=2, ៣=3, ៤=4, ៥=5, ៦=6, ៧=7, ៨=8, ៩=9) to standard digits. "
            . "   - Ignore comma separator or spaces inside numbers (e.g., '1,000' or '1 000' is numeric 1000). "
            . "   - The required booking payment for THIS specific booking is {$expectedUsd} USD (approx " . number_format($expectedKhr) . " KHR at {$khrRate} KHR/USD). "
            . "   - For KHR amounts, calculate equivalent USD = KHR numeric amount / {$khrRate}. "
            . "RULE 2 — Receiver Account Name (CRITICAL): You MUST extract the name of the destination account that RECEIVED the money. "
            . "Check for receiver labels in both English and Khmer: "
            . "+ EN Labels: 'Seller', 'Receiver Name', 'Receiver', 'Recipient', 'To Account', 'To', 'Paid To' "
            . "+ KH Labels: 'អ្នកទទួលប្រាក់', 'ផ្ញើទៅ', 'អ្នកលក់', 'ទូទាត់ប្រាក់ទៅ', 'ទៅគណនី', 'គណនីទទួលប្រាក់', 'ទៅ' "
            . "DO NOT return the SENDER / PAYER / FROM name (អ្នកផ្ញើ / គណនីកាត់ប្រាក់ចេញ / From / Sender / Customer). "
            . "ONLY return the name next to the RECEIVER/SELLER/TO label (destination account). "
            . "The expected receiver name is: {$expectedAccountName}. "
            . "RULE 3 — Transfer Date & Time (STRICT TODAY DEVICE DATE CHECK): Extract the date and time when the transfer was made. "
            . "Check for date labels in both English and Khmer: "
            . "+ EN Labels: 'Date', 'Transaction Date', 'Date & Time', 'Created Date', 'Transfer Date' "
            . "+ KH Labels: 'កាលបរិច្ឆេទ', 'ថ្ងៃខែឆ្នាំ', 'ថ្ងៃទី', 'កាលបរិច្ឆេទប្រតិបត្តិការ' "
            . "Today's EXACT device/server date is: " . date('Y-m-d (d/m/Y)') . ". "
            . "Set 'is_today_date' to TRUE ONLY IF the transfer date on the slip is TODAY (" . date('Y-m-d') . " or " . date('d/m/Y') . "). "
            . "Set 'is_today_date' to FALSE IF the transfer date is yesterday, 1+ days ago, or a previous month/year (reused slip from previous days). "
            . "Extract 'detected_date' as the text string of the date found on the slip. "
            . "RULE 4 — Authenticity: Set is_authentic_slip to true for official bank transfer receipt screenshots or payment success screens with transaction ref numbers. Set false ONLY for pure QR code images with no transaction details, edited images, or unrelated photos. "
            . "Respond ONLY in valid JSON format with no markdown wrappers: "
            . "{\"is_authentic_slip\": true, \"detected_amount_num\": {$expectedUsd}, \"currency\": \"USD\", \"detected_amount_usd\": {$expectedUsd}, \"bank_name\": \"ACLEDA Bank\", \"receiver_name\": \"{$expectedAccountName}\", \"detected_date\": \"" . date('d/m/Y') . "\", \"is_today_date\": true, \"reason\": \"Authentic bank transfer receipt showing exact amount, receiver, and transfer date today\"}";

        $models = ['gemini-3.6-flash', 'gemini-3.5-flash', 'gemini-2.5-flash-image', 'gemini-flash-latest'];

        foreach ($models as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $payload = [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data'      => $base64Image,
                                    ]
                                ],
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json'
                    ]
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 12);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode !== 200 || !$response) {
                    continue;
                }

                $resArr = json_decode($response, true);
                $textResp = $resArr['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!$textResp) {
                    continue;
                }

                // Strip markdown backticks if present
                $textResp = trim(preg_replace('/^```(?:json)?|```$/m', '', $textResp));
                $data = json_decode($textResp, true);
                if (!is_array($data)) {
                    continue;
                }

                $isAuthentic    = $data['is_authentic_slip'] ?? false;
                $currency       = strtoupper($data['currency'] ?? 'USD');
                $detectedUsd    = floatval($data['detected_amount_usd'] ?? 0);
                $detectedAmount = floatval($data['detected_amount_num'] ?? $detectedUsd);
                $bankName       = $data['bank_name'] ?? 'ធនាគារ';
                $receiverName   = strtoupper(trim($data['receiver_name'] ?? ''));
                $detectedDate   = trim($data['detected_date'] ?? '');
                $isTodayDate    = isset($data['is_today_date']) ? (bool)$data['is_today_date'] : true;
                $reason         = $data['reason'] ?? '';

                // ដំណើរការពិនិត្យគ្រប់លក្ខខណ្ឌទាំង ៤ យ៉ាងម៉ឺងម៉ាត់

                // ពិនិត្យភាពពិតប្រាកដនៃបង្កាន់ដៃ
                if (!$isAuthentic) {
                    return [
                        'status'          => 'fake',
                        'badge'           => 'រូបភាពក្លែងក្លាយ',
                        'message'         => "រូបភាពដែលលោកអ្នកបានអាប់ឡូត មិនមែនជា «បង្កាន់ដៃទូទាត់ប្រាក់ធនាគារ» ពិតប្រាកដឡើយ! ({$reason})។ សូមអាប់ឡូតរូបបង្កាន់ដៃចេញពី Mobile Banking App (ABA, ACLEDA, Wing, Bakong...)",
                        'detected_amount' => null,
                        'expected_usd'    => $expectedUsd,
                        'expected_khr'    => $expectedKhr,
                    ];
                }

                // ពិនិត្យឈ្មោះគណនីអ្នកទទួលលុយ
                $accountNameValid = true;
                if (!empty($expectedAccountName)) {
                    if (empty($receiverName)) {
                        return [
                            'status'          => 'wrong_account',
                            'badge'           => 'មិនអាចអានឈ្មោះអ្នកទទួល!',
                            'message'         => "មិនអាចអានឈ្មោះគណនីអ្នកទទួលលុយក្នុងបង្កាន់ដែបង់ប្រាក់បានឡើយ! បង្កាន់ដៃបង់ប្រាក់ត្រូវបង្ហាញឈ្មោះគណនីអ្នកទទួលលុយគឺ <b>{$expectedAccountName}</b> ច្បាស់លាស់។ សូមអាប់ឡូតបង្កាន់ដៃបង់ប្រាក់ឱ្យច្បាស់ ឬទូទាត់ទៅឃ្យូអរកូដត្រឹមត្រូវ!",
                            'detected_amount' => null,
                            'expected_usd'    => $expectedUsd,
                            'expected_khr'    => $expectedKhr,
                        ];
                    }

                    similar_text($receiverName, $expectedAccountName, $pct);
                    $nameMatch = str_contains($receiverName, $expectedAccountName)
                        || str_contains($expectedAccountName, $receiverName)
                        || $pct >= 75;

                    if (!$nameMatch) {
                        return [
                            'status'          => 'wrong_account',
                            'badge'           => 'គណនីមិនត្រឹមត្រូវ',
                            'message'         => "បង្កាន់ដៃនេះបានផ្ទេរប្រាក់មកកាន់ «<b>{$receiverName}</b>» មិនមែន «<b>{$expectedAccountName}</b>» ទេ។ សូមពិនិត្យ និងស្កែនឃ្យូអរកូដឡើងវិញដើម្បីផ្ទេរប្រាក់ទៅកាន់ ({$expectedAccountName})!",
                            'detected_amount' => null,
                            'expected_usd'    => $expectedUsd,
                            'expected_khr'    => $expectedKhr,
                        ];
                    }
                }

                // ពិនិត្យថ្ងៃបាញ់លុយ ត្រូវតែជា «ថ្ងៃនេះ»
                if ($isTodayDate === false) {
                    $dateText = !empty($detectedDate) ? " (ថ្ងៃទី {$detectedDate})" : '';
                    return [
                        'status'          => 'fake',
                        'badge'           => 'បង្កាន់ដៃចាស់មិនមែនថ្ងៃនេះ',
                        'message'         => "({$bankName}) រូបភាពបង្កាន់ដៃបង់ប្រាក់នេះជា «បង្កាន់ដៃចាស់{$dateText}»! ប្រព័ន្ធអនុញ្ញាតតែបង្កាន់ដៃដែលបានទូទាត់ប្រាក់នៅថ្ងៃនេះ <b>" . date('d/m/Y') . "</b> ប៉ុណ្ណោះ។ សូមស្កែនឃ្យូអរកូដ និងទូទាត់ប្រាក់ថ្មីសម្រាប់ថ្ងៃនេះ!",
                        'detected_amount' => null,
                        'expected_usd'    => $expectedUsd,
                        'expected_khr'    => $expectedKhr,
                    ];
                }

                // ពិនិត្យចំនួនទឹកប្រាក់ទូទាត់
                if ($currency === 'KHR' && $detectedAmount > 0 && $detectedUsd == 0) {
                    $detectedUsd = round($detectedAmount / $khrRate, 2);
                }

                $isExactMatch = false;
                if (abs($detectedUsd - $expectedUsd) < 0.05) {
                    $isExactMatch = true;
                } elseif ($currency === 'KHR' && abs($detectedAmount - $expectedKhr) < 500) {
                    $isExactMatch = true;
                }

                $detectedDisplay = ($currency === 'KHR') ? number_format($detectedAmount) . ' ៛ ($' . number_format($detectedUsd, 2) . ')' : '$' . number_format($detectedUsd, 2);

                // នៅពេលឆែកមើលគ្រប់លក្ខខណ្ឌទាំង ៤ រួចរាល់អស់ហើយ ទើបសម្រេចថាត្រឹមត្រូវគ្រប់ចំនួន!
                if ($isExactMatch) {
                    return [
                        'status'          => 'exact',
                        'badge'           => 'ផ្ទៀងផ្ទាត់ជោគជ័យ ១០០%',
                        'message'         => "<b>បានផ្ទៀងផ្ទាត់ជោគជ័យ ({$bankName}) ៖</b> គ្រប់លក្ខខណ្ឌទាំងអស់ត្រូវបានផ្ទៀងផ្ទាត់ត្រឹមត្រូវ ១០០% ៖\n"
                                           . "បង្កាន់ដៃធនាគារពិតប្រាកដ ({$bankName})\n"
                                           . "គណនីអ្នកទទួលលុយត្រឹមត្រូវ ({$expectedAccountName})\n"
                                           . "កាលបរិច្ឆេទផ្ទេរប្រាក់ថ្ងៃនេះ (" . date('d/m/Y') . ")\n"
                                           . "បានទូទាត់ប្រាក់គ្រប់ចំនួន ({$detectedDisplay})",
                        'detected_amount' => $detectedUsd,
                        'expected_usd'    => $expectedUsd,
                        'expected_khr'    => $expectedKhr,
                    ];
                } else {
                    return [
                        'status'          => 'mismatch',
                        'badge'           => 'ចំនួនទឹកប្រាក់មិនត្រូវគ្នា',
                        'message'         => "({$bankName}) រកឃើញចំនួនបង់ប្រាក់ត្រឹមតែ <b>{$detectedDisplay}</b> ប៉ុន្តែប្រព័ន្ធតម្រូវឱ្យបង់ <b>\${$expectedUsd}</b> (" . number_format($expectedKhr) . " ៛)! សូមពិនិត្យឡើងវិញ។",
                        'detected_amount' => $detectedUsd,
                        'expected_usd'    => $expectedUsd,
                        'expected_khr'    => $expectedKhr,
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Gemini Model {$model} Exception: " . $e->getMessage());
                continue;
            }
        }

        return null;
    }

    /**
     * 🇰🇭 Convert Khmer Digits to English Digits
     */
    protected function convertKhmerDigits(string $str): string
    {
        $khmerDigits = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($khmerDigits, $englishDigits, $str);
    }

    /**
     * Check if uploaded image is just KHQR card template or non-bank fake slip
     */
    protected function isQrCardOrFakeImage(string $text, string $filePath): bool
    {
        $lowerText = mb_strtolower($text, 'UTF-8');

        $qrKeywords = [
            'pnt hotel', 'hotel bookings', 'LEAV SIS', 'ស្កែនដើម្បី', 'សណ្ឋាគារ ភីអេនធី',
            'សូមស្កែន', 'សូមស្កែនទូទាត់', ' bakong banking'
        ];

        foreach ($qrKeywords as $kw) {
            if (mb_strpos($lowerText, $kw) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract text from image via Tesseract CLI or free OCR.space API fallback
     */
    protected function extractTextFromImage(string $filePath): string
    {
        if (!file_exists($filePath)) {
            return '';
        }

        // Try Tesseract CLI if installed on OS
        try {
            $tesseractBin = 'tesseract';
            $outputFile = storage_path('app/temp_ocr_' . uniqid());
            $cmd = escapeshellcmd("{$tesseractBin} " . escapeshellarg($filePath) . " {$outputFile} --oem 1 -l eng 2>&1");
            @exec($cmd, $out, $retVal);

            if (file_exists($outputFile . '.txt')) {
                $text = file_get_contents($outputFile . '.txt');
                @unlink($outputFile . '.txt');
                if (!empty(trim($text))) {
                    return $text;
                }
            }
        } catch (\Exception $e) {
            Log::debug('Tesseract CLI not available: ' . $e->getMessage());
        }

        // Try Free OCR.space API fallback
        try {
            $imageData = file_get_contents($filePath);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mime = ($ext === 'png') ? 'image/png' : 'image/jpeg';
            $base64 = 'data:' . $mime . ';base64,' . base64_encode($imageData);

            $url = 'https://api.ocr.space/parse/image';
            $postFields = [
                'apikey'      => 'helloworld',
                'base64Image' => $base64,
                'language'    => 'eng',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            curl_close($ch);

            if ($res) {
                $json = json_decode($res, true);
                $parsedText = $json['ParsedResults'][0]['ParsedText'] ?? '';
                if (!empty(trim($parsedText))) {
                    return $parsedText;
                }
            }
        } catch (\Exception $e) {
            Log::debug('OCR Space API Error: ' . $e->getMessage());
        }

        return '';
    }

    /**
     * Extract numerical amounts from text string
     */
    protected function findAmountsInText(string $text): array
    {
        $amounts = [];

        if (preg_match_all('/(?:\$|USD\s*|KHR\s*|៛\s*)?([0-9]{1,3}(?:,[0-9]{3})*(?:\.[0-9]{1,2})?)/i', $text, $matches)) {
            foreach ($matches[1] as $match) {
                $clean = str_replace(',', '', $match);
                $val = floatval($clean);
                if ($val > 0) {
                    $amounts[] = $val;
                }
            }
        }

        return array_unique($amounts);
    }
}
