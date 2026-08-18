<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HotelBooking;
use App\Models\MeetingBooking;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Message;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Tour;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $readNotifs = session('read_notifications', []);

        $pendingRoomBookings = HotelBooking::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $cancelledRoomBookings = HotelBooking::where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();

        $pendingMeetingBookings = MeetingBooking::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $cancelledMeetingBookings = MeetingBooking::where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();

        $unreadContacts = Contact::where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingReviews = Review::where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadChatMessages = Message::where('user_id', '!=', auth()->id())
            ->where('is_read', 0)
            ->with(['conversation.sender', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications = collect();

        // Pending Room Bookings
        foreach ($pendingRoomBookings as $b) {
            $key = 'room_' . $b->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'room',
                'category' => 'booking',
                'icon' => 'fas fa-bed',
                'icon_bg' => 'bg-amber-500/10 text-amber-500 dark:bg-amber-500/20 dark:text-amber-400',
                'title' => 'ការកក់បន្ទប់ (រង់ចាំការពិនិត្យ)',
                'description' => ($b->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $b->booking_code,
                'time' => $b->created_at ? $b->created_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'room', 'id' => $b->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($b->created_at),
            ]);
        }

        // Cancelled Room Bookings
        foreach ($cancelledRoomBookings as $cb) {
            $key = 'room_cancel_' . $cb->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'cancelled',
                'category' => 'booking',
                'icon' => 'fas fa-times-circle',
                'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                'title' => 'អតិថិជនបោះបង់ការកក់បន្ទប់ ',
                'description' => ($cb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cb->booking_code,
                'time' => $cb->updated_at ? $cb->updated_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'room', 'id' => $cb->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($cb->updated_at ?? $cb->created_at),
            ]);
        }

        // Pending Meeting Bookings
        foreach ($pendingMeetingBookings as $mb) {
            $key = 'meeting_' . $mb->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'meeting',
                'category' => 'booking',
                'icon' => 'fas fa-handshake',
                'icon_bg' => 'bg-blue-500/10 text-blue-500 dark:bg-blue-500/20 dark:text-blue-400',
                'title' => 'ការកក់សាលប្រជុំ (រង់ចាំការពិនិត្យ)',
                'description' => ($mb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $mb->booking_code,
                'time' => $mb->created_at ? $mb->created_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'meeting', 'id' => $mb->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($mb->created_at),
            ]);
        }

        // Cancelled Meeting Bookings
        foreach ($cancelledMeetingBookings as $cmb) {
            $key = 'meeting_cancel_' . $cmb->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'cancelled',
                'category' => 'booking',
                'icon' => 'fas fa-times-circle',
                'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                'title' => 'អតិថិជនបោះបង់ការកក់សាលប្រជុំ ',
                'description' => ($cmb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cmb->booking_code,
                'time' => $cmb->updated_at ? $cmb->updated_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'meeting', 'id' => $cmb->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($cmb->updated_at ?? $cmb->created_at),
            ]);
        }

        // Chat Messages
        foreach ($unreadChatMessages as $cm) {
            $key = 'chat_' . $cm->id;
            $isUnread = !in_array($key, $readNotifs);
            $senderName = $cm->user->name ?? ($cm->conversation->sender->name ?? 'អតិថិជន');
            $notifications->push([
                'id' => $key,
                'type' => 'chat',
                'category' => 'message',
                'icon' => 'fas fa-comments',
                'icon_bg' => 'bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400',
                'title' => 'សារ Chat ថ្មីពី ' . $senderName,
                'description' => Str::limit($cm->message ?: 'រូបភាព/ឯកសារ', 40),
                'time' => $cm->created_at ? $cm->created_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'chat', 'id' => $cm->conversation_id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($cm->created_at),
            ]);
        }

        // Contact Us
        foreach ($unreadContacts as $c) {
            $key = 'contact_' . $c->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'chat',
                'category' => 'message',
                'icon' => 'fas fa-envelope',
                'icon_bg' => 'bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20 dark:text-emerald-400',
                'title' => 'សារទំនាក់ទំនងថ្មី',
                'description' => $c->name . ' (' . ($c->email ?: $c->tell) . ')',
                'time' => $c->created_at ? $c->created_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'contact', 'id' => $c->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($c->created_at),
            ]);
        }

        // Reviews
        foreach ($pendingReviews as $r) {
            $key = 'review_' . $r->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'chat',
                'category' => 'message',
                'icon' => 'fas fa-star',
                'icon_bg' => 'bg-purple-500/10 text-purple-500 dark:bg-purple-500/20 dark:text-purple-400',
                'title' => 'ការវាយតម្លៃថ្មី',
                'description' => ($r->name ?: 'ភ្ញៀវ') . ' - ពិន្ទុ: ' . $r->rating . '/5',
                'time' => $r->created_at ? $r->created_at->locale('km')->diffForHumans() : '',
                'url' => route('admin.notifications.read', ['type' => 'review', 'id' => $r->id]),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($r->created_at),
            ]);
        }

        // Promotions (ប្រូម៉ូសិន - promotions table)
        $promotions = Promotion::where('status', 1)->orWhere('status', 'active')->orderBy('created_at', 'desc')->limit(10)->get();
        foreach ($promotions as $promo) {
            $key = 'promo_' . $promo->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'promo',
                'category' => 'message',
                'icon' => 'fas fa-tags',
                'icon_bg' => 'bg-pink-500/10 text-pink-500 dark:bg-pink-500/20 dark:text-pink-400',
                'title' => Str::limit($promo->title, 50),
                'description' => Str::limit($promo->description ?: 'ប្រូម៉ូសិនចុះថ្លៃពិសេស', 40),
                'time' => $promo->created_at ? $promo->created_at->locale('km')->diffForHumans() : '',
                'url' => route('frontend.rooms'),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($promo->created_at),
            ]);
        }

        // Posts & Events (ព័ត៌មាន & ព្រឹត្តិការណ៍ - posts table)
        $publishedPosts = Post::where('status', 'published')->orderBy('created_at', 'desc')->limit(10)->get();
        foreach ($publishedPosts as $p) {
            $key = 'post_' . $p->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'post',
                'category' => 'message',
                'icon' => 'fas fa-newspaper',
                'icon_bg' => 'bg-purple-500/10 text-purple-500 dark:bg-purple-500/20 dark:text-purple-400',
                'title' => Str::limit($p->title, 50),
                'description' => Str::limit(strip_tags($p->content), 40),
                'time' => $p->created_at ? $p->created_at->locale('km')->diffForHumans() : '',
                'url' => route('frontend.posts_detail', $p->id),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($p->created_at),
            ]);
        }

        // Tourist Attractions (កន្លែងទេសចរណ៍ - tours table)
        $activeTours = Tour::where('status', 1)->orderBy('created_at', 'desc')->limit(10)->get();
        foreach ($activeTours as $t) {
            $key = 'tour_' . $t->id;
            $isUnread = !in_array($key, $readNotifs);
            $notifications->push([
                'id' => $key,
                'type' => 'tour',
                'category' => 'message',
                'icon' => 'fas fa-map-marked-alt',
                'icon_bg' => 'bg-emerald-500/10 text-emerald-500 dark:bg-emerald-500/20 dark:text-emerald-400',
                'title' => Str::limit($t->name, 50),
                'description' => Str::limit(strip_tags($t->description ?: 'កន្លែងទេសចរណ៍គួរឱ្យចាប់អារម្មណ៍'), 40),
                'time' => $t->created_at ? $t->created_at->locale('km')->diffForHumans() : '',
                'url' => route('toursdetail', $t->id),
                'is_unread' => $isUnread,
                'timestamp' => strtotime($t->created_at),
            ]);
        }

        $sortedNotifs = $notifications->sortByDesc('timestamp')->values();

        $page = (int) $request->input('page', 1);
        $perPage = 12;
        $total = count($sortedNotifs);
        $offset = ($page - 1) * $perPage;
        $pagedNotifs = $sortedNotifs->slice($offset, $perPage)->values();
        $hasMore = ($offset + $perPage) < $total;

        if ($request->ajax()) {
            return response()->json([
                'notifications' => $pagedNotifs,
                'has_more'      => $hasMore,
                'page'          => $page,
            ]);
        }

        return view('admin.notifications.index', [
            'sortedNotifs'  => $pagedNotifs,
            'initialNotifs' => $pagedNotifs,
            'hasMore'       => $hasMore,
            'totalCount'    => $total,
        ]);
    }

    public function getNotifications()
    {
        try {
            $readNotifs = session('read_notifications', []);

            $pendingRoomBookings = HotelBooking::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $cancelledRoomBookings = HotelBooking::where('status', 'cancelled')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            $pendingMeetingBookings = MeetingBooking::where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $cancelledMeetingBookings = MeetingBooking::where('status', 'cancelled')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            $unreadContacts = Contact::where('status', 'unread')
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $pendingReviews = Review::where('status', 0)
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $unreadChatMessages = Message::where('user_id', '!=', auth()->id())
                ->where('is_read', 0)
                ->with(['conversation.sender', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();

            $notifications = collect();

            // 1. Room Bookings
            foreach ($pendingRoomBookings as $b) {
                $key = 'room_' . $b->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
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
                    'created_at' => $b->created_at ? $b->created_at->toIso8601String() : '',
                ]);
            }

            foreach ($cancelledRoomBookings as $cb) {
                $key = 'room_cancel_' . $cb->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
                    'id' => $key,
                    'type' => 'booking',
                    'category' => 'room',
                    'icon' => 'fas fa-times-circle',
                    'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                    'title' => 'អតិថិជនបោះបង់ការកក់បន្ទប់ ',
                    'description' => ($cb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cb->booking_code,
                    'time' => $cb->updated_at ? $cb->updated_at->locale('km')->diffForHumans() : '',
                    'url' => route('admin.notifications.read', ['type' => 'room', 'id' => $cb->id]),
                    'is_unread' => $isUnread,
                    'created_at' => $cb->updated_at ? $cb->updated_at->toIso8601String() : ($cb->created_at ? $cb->created_at->toIso8601String() : ''),
                ]);
            }

            // 2. Meeting Bookings
            foreach ($pendingMeetingBookings as $mb) {
                $key = 'meeting_' . $mb->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
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
                    'created_at' => $mb->created_at ? $mb->created_at->toIso8601String() : '',
                ]);
            }

            foreach ($cancelledMeetingBookings as $cmb) {
                $key = 'meeting_cancel_' . $cmb->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
                    'id' => $key,
                    'type' => 'booking',
                    'category' => 'meeting',
                    'icon' => 'fas fa-times-circle',
                    'icon_bg' => 'bg-rose-500/10 text-rose-500 dark:bg-rose-500/20 dark:text-rose-400',
                    'title' => 'អតិថិជនបោះបង់ការកក់សាលប្រជុំ',
                    'description' => ($cmb->customer_name ?: 'អតិថិជន') . ' - កូដ: #' . $cmb->booking_code,
                    'time' => $cmb->updated_at ? $cmb->updated_at->locale('km')->diffForHumans() : '',
                    'url' => route('admin.notifications.read', ['type' => 'meeting', 'id' => $cmb->id]),
                    'is_unread' => $isUnread,
                    'created_at' => $cmb->updated_at ? $cmb->updated_at->toIso8601String() : ($cmb->created_at ? $cmb->created_at->toIso8601String() : ''),
                ]);
            }

            // 3. Chat Messages
            foreach ($unreadChatMessages as $cm) {
                $key = 'chat_' . $cm->id;
                $isUnread = !in_array($key, $readNotifs);
                $senderName = $cm->user->name ?? ($cm->conversation->sender->name ?? 'អតិថិជន');
                $notifications->push([
                    'id' => $key,
                    'type' => 'message',
                    'category' => 'chat',
                    'icon' => 'fas fa-comments',
                    'icon_bg' => 'bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-400',
                    'title' => 'សារ Chat ថ្មីពី ' . $senderName,
                    'description' => Str::limit($cm->message ?: 'រូបភាព/ឯកសារ', 30),
                    'time' => $cm->created_at ? $cm->created_at->locale('km')->diffForHumans() : '',
                    'url' => route('admin.notifications.read', ['type' => 'chat', 'id' => $cm->conversation_id]),
                    'is_unread' => $isUnread,
                    'created_at' => $cm->created_at ? $cm->created_at->toIso8601String() : '',
                ]);
            }

            // 4. Contact Us
            foreach ($unreadContacts as $c) {
                $key = 'contact_' . $c->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
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
                    'created_at' => $c->created_at ? $c->created_at->toIso8601String() : '',
                ]);
            }

            // 5. Reviews
            foreach ($pendingReviews as $r) {
                $key = 'review_' . $r->id;
                $isUnread = !in_array($key, $readNotifs);
                $notifications->push([
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
                    'created_at' => $r->created_at ? $r->created_at->toIso8601String() : '',
                ]);
            }

            $notifications = $notifications->sortByDesc('created_at')->values();
            $unreadCount = $notifications->where('is_unread', true)->count();

            if ($unreadCount == 0 && $notifications->count() == 0) {
                $recentRoomBookings = HotelBooking::orderBy('created_at', 'desc')->limit(10)->get();
                foreach ($recentRoomBookings as $b) {
                    $notifications->push([
                        'id' => 'room_' . $b->id,
                        'type' => 'booking',
                        'category' => 'room',
                        'icon' => 'fas fa-bed',
                        'icon_bg' => 'bg-gray-500/10 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                        'title' => 'ការកក់បន្ទប់ (' . HotelBooking::statusLabel($b->status) . ')',
                        'description' => ($b->customer_name ?: 'អតិថិជន') . ' - កូដ: ' . $b->booking_code,
                        'time' => $b->created_at ? $b->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('room-bookings.index', ['search' => $b->booking_code]),
                        'is_unread' => false,
                        'created_at' => $b->created_at ? $b->created_at->toIso8601String() : '',
                    ]);
                }
                $recentContacts = Contact::orderBy('created_at', 'desc')->limit(10)->get();
                foreach ($recentContacts as $c) {
                    $notifications->push([
                        'id' => 'contact_' . $c->id,
                        'type' => 'message',
                        'category' => 'contact',
                        'icon' => 'fas fa-envelope',
                        'icon_bg' => 'bg-gray-500/10 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                        'title' => 'សារទំនាក់ទំនង',
                        'description' => $c->name . ' - ' . Str::limit($c->description, 25),
                        'time' => $c->created_at ? $c->created_at->locale('km')->diffForHumans() : '',
                        'url' => route('contact.index', ['search' => $c->name]),
                        'is_unread' => false,
                        'created_at' => $c->created_at ? $c->created_at->toIso8601String() : '',
                    ]);
                }
                $notifications = $notifications->sortByDesc('created_at')->values();
            }

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'notifications' => $notifications,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'unread_count' => 0,
                'notifications' => [],
            ], 500);
        }
    }

    public function readAndRedirect($type, $id)
    {
        try {
            $readNotifs = session('read_notifications', []);
            $key = $type . '_' . $id;
            if (!in_array($key, $readNotifs)) {
                $readNotifs[] = $key;
                session(['read_notifications' => $readNotifs]);
            }

            if ($type === 'contact') {
                Contact::where('id', $id)->update(['status' => 'completed']);
                $contact = Contact::find($id);
                return redirect()->route('contact.index', $contact ? ['search' => $contact->name] : []);
            }

            if ($type === 'review') {
                Review::where('id', $id)->update(['status' => 1]);
                return redirect()->route('reviews.index');
            }

            if ($type === 'chat') {
                Message::where('conversation_id', $id)
                    ->where('user_id', '!=', auth()->id())
                    ->where('is_read', 0)
                    ->update(['is_read' => 1]);
                return redirect()->route('messages.index', ['conversation_id' => $id]);
            }

            if ($type === 'room' || $type === 'room_cancel') {
                $booking = HotelBooking::find($id);
                return redirect()->route('room-bookings.index', $booking ? ['search' => $booking->booking_code] : []);
            }

            if ($type === 'meeting' || $type === 'meeting_cancel') {
                $booking = MeetingBooking::find($id);
                return redirect()->route('meeting-bookings.index', $booking ? ['search' => $booking->booking_code] : []);
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('dashboard');
        }
    }

    public function markAllAsRead()
    {
        try {
            Contact::where('status', 'unread')->update(['status' => 'completed']);
            Review::where('status', 0)->update(['status' => 1]);
            Message::where('user_id', '!=', auth()->id())
                ->where('is_read', 0)
                ->update(['is_read' => 1]);

            $pendingRoom = HotelBooking::pluck('id')->map(fn($id) => 'room_' . $id)->toArray();
            $cancelledRoom = HotelBooking::pluck('id')->map(fn($id) => 'room_cancel_' . $id)->toArray();

            $pendingMeeting = MeetingBooking::pluck('id')->map(fn($id) => 'meeting_' . $id)->toArray();
            $cancelledMeeting = MeetingBooking::pluck('id')->map(fn($id) => 'meeting_cancel_' . $id)->toArray();

            $chatKeys = Message::pluck('id')->map(fn($id) => 'chat_' . $id)->toArray();
            $contactKeys = Contact::pluck('id')->map(fn($id) => 'contact_' . $id)->toArray();
            $reviewKeys = Review::pluck('id')->map(fn($id) => 'review_' . $id)->toArray();
            $tourKeys = Tour::pluck('id')->map(fn($id) => 'tour_' . $id)->toArray();
            $promoKeys = Promotion::pluck('id')->map(fn($id) => 'promo_' . $id)->toArray();
            $postKeys = Post::pluck('id')->map(fn($id) => 'post_' . $id)->toArray();

            $allKeys = array_merge(
                session('read_notifications', []),
                $pendingRoom,
                $cancelledRoom,
                $pendingMeeting,
                $cancelledMeeting,
                $chatKeys,
                $contactKeys,
                $reviewKeys,
                $tourKeys,
                $promoKeys,
                $postKeys
            );
            session(['read_notifications' => array_unique($allKeys)]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
