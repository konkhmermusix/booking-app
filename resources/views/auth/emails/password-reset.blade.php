<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <title>កំណត់លេខសម្ងាត់ឡើងវិញ</title>
</head>

<body style="font-family: 'Hanuman', 'Khmer OS Battambang', sans-serif; background-color: #f4f4f7; padding: 30px; margin: 0;">
    <div style="max-w: 570px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e8e8e8;">

        <h2 style="color: #002B5B; font-size: 20px; font-weight: bold; margin-bottom: 20px; border-bottom: 2px solid #f4f4f7; padding-bottom: 10px;">
            សណ្ឋាគារ ភីអេនធី
        </h2>

        <p style="color: #51545e; font-size: 14px; line-height: 1.6;">ជម្រាបសួរ,</p>
        <p style="color: #51545e; font-size: 14px; line-height: 1.6;">
            លោកអ្នកបានស្នើសុំកំណត់លេខសម្ងាត់ឡើងវិញ សម្រាប់គណនីដែលភ្ជាប់ជាមួយអ៊ីមែលនេះ (<strong style="color: #002B5B;">{{ $email }}</strong>)។
        </p>
        <p style="color: #51545e; font-size: 14px; line-height: 1.6; margin-bottom: 30px;">
            សូមចុចលើប៊ូតុងខាងក្រោមដើម្បីបង្កើតលេខសម្ងាត់ថ្មី៖
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('reset-password/'.$token.'?email='.$email) }}"
                style="background-color: #2563eb; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 4px 6px rgba(37,99,235,0.2);">
                កំណត់ពាក្យសម្ងាត់ថ្មី
            </a>
        </div>

        <p style="color: #9ca3af; font-size: 12px; line-height: 1.6; border-t: 1px solid #f4f4f7; padding-top: 20px;">
            តំណភ្ជាប់នេះមានសុពលភាពត្រឹមតែ ៦០ នាទីប៉ុណ្ណោះ។ ប្រសិនបើលោកអ្នកមិនបានស្នើសុំការផ្លាស់ប្តូរនេះទេ សូមរំលងអ៊ីមែលនេះដោយសុវត្ថិភាព។
        </p>
    </div>
</body>

</html>