<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\ContactSetting;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'http') {
            URL::forceScheme('https');
        } elseif (str_starts_with(config('app.url'), 'http://')) {
            URL::forceScheme('http');
        }

        View::composer('*', function ($view) {
            $khrRate = 4050;
            try {
                $settings = ContactSetting::where('status', 1)->get();
                $contactSettings = [];
                foreach ($settings as $setting) {
                    if ($setting->key) {
                        $keyLower = strtolower($setting->key);
                        $contactSettings[$keyLower] = $setting->value;
                        if (in_array($keyLower, ['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate']) && is_numeric($setting->value) && (float)$setting->value > 0) {
                            $khrRate = (float) $setting->value;
                        }
                    }
                    if ($setting->label) {
                        $labelKey = strtolower(trim($setting->label));
                        if (!isset($contactSettings[$labelKey])) {
                            $contactSettings[$labelKey] = $setting->value;
                        }
                    }
                }
            } catch (\Exception $e) {
                $contactSettings = [];
            }

            $dynPhone = $contactSettings['phone'] ?? $contactSettings['លេខទូរស័ព្ទ'] ?? '096 71 1979 8 / 071 4 71 1979';
            $dynEmail = $contactSettings['email'] ?? $contactSettings['អ៊ីមែល'] ?? 'pntpalace@gmail.com';
            $dynAddress = $contactSettings['address'] ?? $contactSettings['location'] ?? $contactSettings['អាសយដ្ឋាន'] ?? 'ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្តត្បូងឃ្មុំ (ខាងកើតរង្វង់មូល ប្រាំមួយមករា)';
            $dynLogo = $contactSettings['site_logo'] ?? $contactSettings['logo'] ?? $contactSettings['hotel_logo'] ?? $contactSettings['logo_image'] ?? null;
            $dynSiteName = $contactSettings['site_name'] ?? $contactSettings['hotel_name'] ?? $contactSettings['name'] ?? $contactSettings['ឈ្មោះសណ្ឋាគារ'] ?? 'ភីអេនធី ផាលេស';
            $dynLogoUrl = !empty($dynLogo)
                ? (str_starts_with($dynLogo, 'http') ? $dynLogo : (str_starts_with($dynLogo, 'images/') || str_starts_with($dynLogo, 'storage/') ? asset($dynLogo) : asset('storage/' . $dynLogo)))
                : asset('images/logo/P&t Palace Hotel.png');

            $contactSettings['khr_rate'] = $khrRate;
            $contactSettings['phone'] = $dynPhone;
            $contactSettings['email'] = $dynEmail;
            $contactSettings['address'] = $dynAddress;
            $contactSettings['site_name'] = $dynSiteName;
            $contactSettings['logo_url'] = $dynLogoUrl;
            if (!isset($contactSettings['logo'])) {
                $contactSettings['logo'] = $dynLogoUrl;
            }

            $view->with('contactSettings', $contactSettings)->with('khrRate', $khrRate);
        });

        View::composer('layouts.admin', function ($view) {
            try {
                $readNotifs = session('read_notifications', []);

                $pendingRoomBookings = \App\Models\HotelBooking::where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();

                $cancelledRoomBookings = \App\Models\HotelBooking::where('status', 'cancelled')
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get();

                $pendingMeetingBookings = \App\Models\MeetingBooking::where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();

                $cancelledMeetingBookings = \App\Models\MeetingBooking::where('status', 'cancelled')
                    ->orderBy('updated_at', 'desc')
                    ->limit(10)
                    ->get();

                $unreadContacts = \App\Models\Contact::where('status', 'unread')
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();

                $pendingReviews = \App\Models\Review::where('status', 0)
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();

                $unreadChatMessages = \App\Models\Message::where('user_id', '!=', auth()->id())
                    ->where('is_read', 0)
                    ->with(['conversation.sender', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();

                $adminNotifications = collect();

                foreach ($pendingRoomBookings as $b) {
                    $key = 'room_' . $b->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'booking',
                        'category' => 'room',
                        'icon' => 'fas fa-bed',
                        'icon_bg' => 'bg-amber-500/10 text-amber-500 dark:bg-amber-500/20 dark:text-amber-400',
                        'title' => 'ការកក់បន្ទប់ (រង់ចាំការបញ្ជាក់)',
                        'description' => ($b->customer_name ?: 'អតិថិជន') . ' - កូដ: ' . $b->booking_code,
                        'time' => $b->created_at ? $b->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'room', 'id' => $b->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $b->created_at,
                    ]);
                }

                foreach ($cancelledRoomBookings as $cb) {
                    $key = 'room_cancel_' . $cb->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'booking',
                        'category' => 'room',
                        'icon' => 'fas fa-times-circle',
                        'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                        'title' => 'អតិថិជនបោះបង់ការកក់បន្ទប់',
                        'description' => ($cb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cb->booking_code,
                        'time' => $cb->updated_at ? $cb->updated_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'room_cancel', 'id' => $cb->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $cb->updated_at ?: $cb->created_at,
                    ]);
                }

                foreach ($pendingMeetingBookings as $mb) {
                    $key = 'meeting_' . $mb->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'booking',
                        'category' => 'meeting',
                        'icon' => 'fas fa-handshake',
                        'icon_bg' => 'bg-blue-500/10 text-blue-500 dark:bg-blue-500/20 dark:text-blue-400',
                        'title' => 'ការកក់សាលប្រជុំ (រង់ចាំការបញ្ជាក់)',
                        'description' => ($mb->customer_name ?: 'អតិថិជន') . ' - កូដ: ' . $mb->booking_code,
                        'time' => $mb->created_at ? $mb->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'meeting', 'id' => $mb->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $mb->created_at,
                    ]);
                }

                foreach ($cancelledMeetingBookings as $cmb) {
                    $key = 'meeting_cancel_' . $cmb->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'booking',
                        'category' => 'meeting',
                        'icon' => 'fas fa-times-circle',
                        'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                        'title' => 'អតិថិជនបោះបង់ការកក់សាលប្រជុំ',
                        'description' => ($cmb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cmb->booking_code,
                        'time' => $cmb->updated_at ? $cmb->updated_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'meeting_cancel', 'id' => $cmb->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $cmb->updated_at ?: $cmb->created_at,
                    ]);
                }

                foreach ($unreadChatMessages as $cm) {
                    $key = 'chat_' . $cm->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $senderName = $cm->user->name ?? ($cm->conversation->sender->name ?? 'អតិថិជន');
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'message',
                        'category' => 'chat',
                        'icon' => 'fas fa-comments',
                        'icon_bg' => 'bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400',
                        'title' => 'សារ Chat ថ្មីពី ' . $senderName,
                        'description' => \Illuminate\Support\Str::limit($cm->message ?: 'រូបភាព/ឯកសារ', 30),
                        'time' => $cm->created_at ? $cm->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'chat', 'id' => $cm->conversation_id]),
                        'is_unread' => $isUnread,
                        'created_at' => $cm->created_at,
                    ]);
                }

                foreach ($unreadContacts as $c) {
                    $key = 'contact_' . $c->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'message',
                        'category' => 'contact',
                        'icon' => 'fas fa-envelope',
                        'icon_bg' => 'bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20 dark:text-emerald-400',
                        'title' => 'សារទំនាក់ទំនងថ្មី',
                        'description' => $c->name . ' (' . ($c->email ?: $c->tell) . ')',
                        'time' => $c->created_at ? $c->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'contact', 'id' => $c->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $c->created_at,
                    ]);
                }

                foreach ($pendingReviews as $r) {
                    $key = 'review_' . $r->id;
                    $isUnread = !in_array($key, $readNotifs);
                    $adminNotifications->push((object)[
                        'id' => $key,
                        'type' => 'message',
                        'category' => 'review',
                        'icon' => 'fas fa-star',
                        'icon_bg' => 'bg-purple-500/10 text-purple-500 dark:bg-purple-500/20 dark:text-purple-400',
                        'title' => 'ការវាយតម្លៃថ្មី',
                        'description' => ($r->name ?: 'ភ្ញៀវ') . ' - ពិន្ទុ: ' . $r->rating . '/5',
                        'time' => $r->created_at ? $r->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('admin.notifications.read', ['type' => 'review', 'id' => $r->id]),
                        'is_unread' => $isUnread,
                        'created_at' => $r->created_at,
                    ]);
                }

                $adminNotifications = $adminNotifications->sortByDesc('created_at')->values();
                $adminUnreadCount = $adminNotifications->where('is_unread', true)->count();

                if ($adminUnreadCount == 0) {
                    $recentRoomBookings = \App\Models\HotelBooking::orderBy('created_at', 'desc')->limit(10)->get();
                    foreach ($recentRoomBookings as $b) {
                        $adminNotifications->push((object)[
                            'id' => 'room_' . $b->id,
                            'type' => 'booking',
                            'category' => 'room',
                            'icon' => 'fas fa-bed',
                            'icon_bg' => 'bg-gray-500/10 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                            'title' => 'ការកក់បន្ទប់ (' . \App\Models\HotelBooking::statusLabel($b->status) . ')',
                            'description' => ($b->customer_name ?: 'អតិថិជន') . ' - កូដ: ' . $b->booking_code,
                            'time' => $b->created_at ? $b->created_at->locale('km')->diffForHumans() : '',
                            'url' => route('room-bookings.index', ['search' => $b->booking_code]),
                            'is_unread' => false,
                            'created_at' => $b->created_at,
                        ]);
                    }
                    $recentContacts = \App\Models\Contact::orderBy('created_at', 'desc')->limit(10)->get();
                    foreach ($recentContacts as $c) {
                        $adminNotifications->push((object)[
                            'id' => 'contact_' . $c->id,
                            'type' => 'message',
                            'category' => 'contact',
                            'icon' => 'fas fa-envelope',
                            'icon_bg' => 'bg-gray-500/10 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                            'title' => 'សារទំនាក់ទំនង',
                            'description' => $c->name . ' - ' . \Illuminate\Support\Str::limit($c->description, 25),
                            'time' => $c->created_at ? $c->created_at->locale('km')->diffForHumans() : '',
                            'url' => route('contact.index', ['search' => $c->name]),
                            'is_unread' => false,
                            'created_at' => $c->created_at,
                        ]);
                    }
                    $adminNotifications = $adminNotifications->sortByDesc('created_at')->values();
                }

                $view->with('adminNotifications', $adminNotifications)
                    ->with('adminUnreadCount', $adminUnreadCount);
            } catch (\Exception $e) {
                $view->with('adminNotifications', collect())
                    ->with('adminUnreadCount', 0);
            }
        });
    }
}
