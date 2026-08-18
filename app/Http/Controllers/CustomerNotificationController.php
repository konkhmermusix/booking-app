<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Tour;
use Illuminate\Support\Str;

class CustomerNotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $readNotifs = session('read_customer_notifications', []);
        $page = (int) $request->input('page', 1);
        $perPage = 12;
        $limit = $perPage * $page;

        // Hotel Bookings updates
        $hotelBookings = DB::table('hotel_bookings')
            ->leftJoin('payments', 'hotel_bookings.id', '=', 'payments.hotel_booking_id')
            ->leftJoin('rooms', 'hotel_bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('hotel_bookings.user_id', $userId)
            ->select(
                'hotel_bookings.id',
                'hotel_bookings.booking_code',
                'hotel_bookings.status',
                'hotel_bookings.updated_at',
                'hotel_bookings.created_at',
                'payments.status as payment_status',
                'room_types.name as room_type_name'
            )
            ->orderBy('hotel_bookings.updated_at', 'desc')
            ->limit($limit)
            ->get();

        // Meeting Bookings updates
        $meetingBookings = DB::table('meeting_bookings')
            ->leftJoin('payments', 'meeting_bookings.id', '=', 'payments.meeting_booking_id')
            ->leftJoin('rooms', 'meeting_bookings.meeting_room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('meeting_bookings.user_id', $userId)
            ->select(
                'meeting_bookings.id',
                'meeting_bookings.booking_code',
                'meeting_bookings.status',
                'meeting_bookings.updated_at',
                'meeting_bookings.created_at',
                'payments.status as payment_status',
                'room_types.name as room_type_name'
            )
            ->orderBy('meeting_bookings.updated_at', 'desc')
            ->limit($limit)
            ->get();

        // Chat messages from Admin
        $chatMessages = Message::where('user_id', '!=', $userId)
            ->whereHas('conversation', function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('sender_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $user = Auth::user();
        $userCreatedAt = $user ? $user->created_at : null;

        // Published Posts & Events
        $publishedPostsQuery = Post::where('status', 'published');
        if ($userCreatedAt) {
            $publishedPostsQuery->where('created_at', '>=', $userCreatedAt);
        }
        $publishedPosts = $publishedPostsQuery->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $notifications = collect();

        foreach ($hotelBookings as $hb) {
            $key = 'cust_hotel_' . $hb->id . '_' . $hb->status . '_' . ($hb->payment_status ?? 'unpaid');
            $isUnread = !in_array($key, $readNotifs);

            $statusText = 'រង់ចាំការពិនិត្យ';
            $icon = 'fas fa-clock';
            $iconBg = 'bg-amber-500/10 text-amber-500';

            if ($hb->status === 'approved' || $hb->status === 'confirmed') {
                $statusText = 'ត្រូវបានបញ្ជាក់ដោយជោគជ័យ';
                $icon = 'fas fa-check-circle';
                $iconBg = 'bg-emerald-500/10 text-emerald-500';
            } elseif ($hb->status === 'cancelled') {
                $statusText = 'ត្រូវបានបោះបង់ ';
                $icon = 'fas fa-times-circle';
                $iconBg = 'bg-rose-500/10 text-rose-500';
            }

            $timeStr = $hb->updated_at ? \Carbon\Carbon::parse($hb->updated_at)->locale('km')->diffForHumans() : '';

            $notifications->push([
                'id'          => $key,
                'type'        => 'hotel',
                'title'       => 'ការកក់បន្ទប់ #' . $hb->booking_code,
                'description' => ($hb->room_type_name ?? 'បន្ទប់ស្នាក់នៅ') . ' - ស្ថានភាព: ' . $statusText,
                'icon'        => $icon,
                'icon_bg'     => $iconBg,
                'time'        => $timeStr,
                'url'         => route('receipt', $hb->booking_code),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($hb->updated_at ?? $hb->created_at),
            ]);
        }

        foreach ($meetingBookings as $mb) {
            $key = 'cust_meeting_' . $mb->id . '_' . $mb->status . '_' . ($mb->payment_status ?? 'unpaid');
            $isUnread = !in_array($key, $readNotifs);

            $statusText = 'រង់ចាំការពិនិត្យ';
            $icon = 'fas fa-clock';
            $iconBg = 'bg-amber-500/10 text-amber-500';

            if ($mb->status === 'approved' || $mb->status === 'confirmed') {
                $statusText = 'ត្រូវបានបញ្ជាក់ដោយជោគជ័យ';
                $icon = 'fas fa-check-circle';
                $iconBg = 'bg-emerald-500/10 text-emerald-500';
            } elseif ($mb->status === 'cancelled') {
                $statusText = 'ត្រូវបានបោះបង់';
                $icon = 'fas fa-times-circle';
                $iconBg = 'bg-rose-500/10 text-rose-500';
            }

            $timeStr = $mb->updated_at ? \Carbon\Carbon::parse($mb->updated_at)->locale('km')->diffForHumans() : '';

            $notifications->push([
                'id'          => $key,
                'type'        => 'meeting',
                'title'       => 'ការកក់សាលប្រជុំ #' . $mb->booking_code,
                'description' => ($mb->room_type_name ?? 'សាលប្រជុំ') . ' - ស្ថានភាព: ' . $statusText,
                'icon'        => $icon,
                'icon_bg'     => $iconBg,
                'time'        => $timeStr,
                'url'         => route('receipt', $mb->booking_code),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($mb->updated_at ?? $mb->created_at),
            ]);
        }

        foreach ($chatMessages as $cm) {
            $key = 'cust_chat_' . $cm->id;
            $isUnread = !in_array($key, $readNotifs) && $cm->is_read == 0;

            $notifications->push([
                'id'          => $key,
                'type'        => 'chat',
                'title'       => 'សារពីផ្នែកសេវាកម្មអតិថិជន',
                'description' => Str::limit($cm->message ?: 'រូបភាព/ឯកសារ', 40),
                'icon'        => 'fas fa-comments',
                'icon_bg'     => 'bg-blue-500/10 text-blue-500',
                'time'        => $cm->created_at ? $cm->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('chat.index'),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($cm->created_at),
            ]);
        }

        foreach ($publishedPosts as $p) {
            $key = 'cust_post_' . $p->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'type'        => 'post',
                'title'       => Str::limit($p->title, 20),
                'description' => Str::limit(strip_tags($p->content), 40),
                'icon'        => 'fas fa-newspaper',
                'icon_bg'     => 'bg-purple-500/10 text-purple-500',
                'time'        => $p->created_at ? $p->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('frontend.posts_detail', $p->id),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($p->created_at),
            ]);
        }

        $activePromotionsQuery = Promotion::where(function ($q) {
            $q->where('status', 1)->orWhere('status', 'active');
        });
        if ($userCreatedAt) {
            $activePromotionsQuery->where('created_at', '>=', $userCreatedAt);
        }
        $activePromotions = $activePromotionsQuery->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        foreach ($activePromotions as $promo) {
            $key = 'cust_promo_' . $promo->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'type'        => 'promo',
                'title'       => Str::limit($promo->title, 20),
                'description' => Str::limit($promo->description ?: 'ប្រូម៉ូសិនចុះថ្លៃពិសេស', 40),
                'icon'        => 'fas fa-tags',
                'icon_bg'     => 'bg-pink-500/10 text-pink-500',
                'time'        => $promo->created_at ? $promo->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('frontend.rooms'),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($promo->created_at),
            ]);
        }

        $activeToursQuery = Tour::where('status', 1);
        if ($userCreatedAt) {
            $activeToursQuery->where('created_at', '>=', $userCreatedAt);
        }
        $activeTours = $activeToursQuery->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        foreach ($activeTours as $t) {
            $key = 'cust_tour_' . $t->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'type'        => 'tour',
                'title'       => Str::limit($t->name, 20),
                'description' => Str::limit(strip_tags($t->description ?: 'កន្លែងទេសចរណ៍គួរឱ្យចាប់អារម្មណ៍'), 40),
                'icon'        => 'fas fa-map-marked-alt',
                'icon_bg'     => 'bg-emerald-500/10 text-emerald-500',
                'time'        => $t->created_at ? $t->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('toursdetail', $t->id),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($t->created_at),
            ]);
        }

        $sortedNotifs = $notifications->sortByDesc('timestamp')->values();

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

        return view('frontend.notifications', [
            'sortedNotifs'  => $pagedNotifs,
            'initialNotifs' => $pagedNotifs,
            'hasMore'       => $hasMore,
            'totalCount'    => $total,
        ]);
    }

    public function getNotifications()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $userId = Auth::id();
        $readNotifs = session('read_customer_notifications', []);

        $hotelBookings = DB::table('hotel_bookings')
            ->leftJoin('payments', 'hotel_bookings.id', '=', 'payments.hotel_booking_id')
            ->leftJoin('rooms', 'hotel_bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('hotel_bookings.user_id', $userId)
            ->select(
                'hotel_bookings.id',
                'hotel_bookings.booking_code',
                'hotel_bookings.status',
                'hotel_bookings.updated_at',
                'hotel_bookings.created_at',
                'payments.status as payment_status',
                'room_types.name as room_type_name'
            )
            ->orderBy('hotel_bookings.updated_at', 'desc')
            ->limit(10)
            ->get();

        $meetingBookings = DB::table('meeting_bookings')
            ->leftJoin('payments', 'meeting_bookings.id', '=', 'payments.meeting_booking_id')
            ->leftJoin('rooms', 'meeting_bookings.meeting_room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('meeting_bookings.user_id', $userId)
            ->select(
                'meeting_bookings.id',
                'meeting_bookings.booking_code',
                'meeting_bookings.status',
                'meeting_bookings.updated_at',
                'meeting_bookings.created_at',
                'payments.status as payment_status',
                'room_types.name as room_type_name'
            )
            ->orderBy('meeting_bookings.updated_at', 'desc')
            ->limit(10)
            ->get();

        $unreadChatMessages = Message::where('user_id', '!=', $userId)
            ->whereHas('conversation', function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('sender_id', $userId);
            })
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $notifications = collect();

        foreach ($hotelBookings as $hb) {
            $key = 'cust_hotel_' . $hb->id . '_' . $hb->status . '_' . ($hb->payment_status ?? 'unpaid');
            $isUnread = !in_array($key, $readNotifs);

            $statusText = 'រង់ចាំការពិនិត្យ';
            $icon = 'fas fa-clock';
            $iconBg = 'bg-amber-500/10 text-amber-500';

            if ($hb->status === 'approved' || $hb->status === 'confirmed') {
                $statusText = 'ត្រូវបានបញ្ជាក់ដោយជោគជ័យ!';
                $icon = 'fas fa-check-circle';
                $iconBg = 'bg-emerald-500/10 text-emerald-500';
            } elseif ($hb->status === 'cancelled') {
                $statusText = 'ត្រូវបានបោះបង់';
                $icon = 'fas fa-times-circle';
                $iconBg = 'bg-rose-500/10 text-rose-500';
            }

            $timeStr = $hb->updated_at ? \Carbon\Carbon::parse($hb->updated_at)->locale('km')->diffForHumans() : '';

            $notifications->push([
                'id'          => $key,
                'title'       => 'ការកក់បន្ទប់ #' . $hb->booking_code,
                'description' => ($hb->room_type_name ?? 'បន្ទប់ស្នាក់នៅ') . ' - ស្ថានភាព: ' . $statusText,
                'icon'        => $icon,
                'icon_bg'     => $iconBg,
                'time'        => $timeStr,
                'url'         => route('receipt', $hb->booking_code),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($hb->updated_at ?? $hb->created_at),
            ]);
        }

        foreach ($meetingBookings as $mb) {
            $key = 'cust_meeting_' . $mb->id . '_' . $mb->status . '_' . ($mb->payment_status ?? 'unpaid');
            $isUnread = !in_array($key, $readNotifs);

            $statusText = 'រង់ចាំការពិនិត្យ';
            $icon = 'fas fa-clock';
            $iconBg = 'bg-amber-500/10 text-amber-500';

            if ($mb->status === 'approved' || $mb->status === 'confirmed') {
                $statusText = 'ត្រូវបានបញ្ជាក់ដោយជោគជ័យ';
                $icon = 'fas fa-check-circle';
                $iconBg = 'bg-emerald-500/10 text-emerald-500';
            } elseif ($mb->status === 'cancelled') {
                $statusText = 'ត្រូវបានបោះបង់';
                $icon = 'fas fa-times-circle';
                $iconBg = 'bg-rose-500/10 text-rose-500';
            }

            $timeStr = $mb->updated_at ? \Carbon\Carbon::parse($mb->updated_at)->locale('km')->diffForHumans() : '';

            $notifications->push([
                'id'          => $key,
                'title'       => 'ការកក់សាលប្រជុំ #' . $mb->booking_code,
                'description' => ($mb->room_type_name ?? 'សាលប្រជុំ') . ' - ស្ថានភាព: ' . $statusText,
                'icon'        => $icon,
                'icon_bg'     => $iconBg,
                'time'        => $timeStr,
                'url'         => route('receipt', $mb->booking_code),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($mb->updated_at ?? $mb->created_at),
            ]);
        }

        foreach ($unreadChatMessages as $cm) {
            $key = 'cust_chat_' . $cm->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'title'       => 'សារថ្មីពីផ្នែកសេវាកម្មអតិថិជន',
                'description' => Str::limit($cm->message ?: 'រូបភាព/ឯកសារ', 35),
                'icon'        => 'fas fa-comments',
                'icon_bg'     => 'bg-blue-500/10 text-blue-500',
                'time'        => $cm->created_at ? $cm->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('chat.index'),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($cm->created_at),
            ]);
        }

        $user = Auth::user();
        $userCreatedAt = $user ? $user->created_at : null;

        $recentPostsQuery = Post::where('status', 'published');
        if ($userCreatedAt) {
            $recentPostsQuery->where('created_at', '>=', $userCreatedAt);
        }
        $recentPosts = $recentPostsQuery->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentPosts as $p) {
            $key = 'cust_post_' . $p->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'title'       => Str::limit($p->title, 15),
                'description' => Str::limit(strip_tags($p->content), 35),
                'icon'        => 'fas fa-newspaper',
                'icon_bg'     => 'bg-purple-500/10 text-purple-500',
                'time'        => $p->created_at ? $p->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('frontend.posts_detail', $p->id),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($p->created_at),
            ]);
        }

        $recentPromosQuery = Promotion::where(function ($q) {
            $q->where('status', 1)->orWhere('status', 'active');
        });
        if ($userCreatedAt) {
            $recentPromosQuery->where('created_at', '>=', $userCreatedAt);
        }
        $recentPromos = $recentPromosQuery->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentPromos as $promo) {
            $key = 'cust_promo_' . $promo->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'title'       => Str::limit($promo->title, 15),
                'description' => Str::limit($promo->description ?: 'ចុះថ្លៃពិសេស', 35),
                'icon'        => 'fas fa-tags',
                'icon_bg'     => 'bg-pink-500/10 text-pink-500',
                'time'        => $promo->created_at ? $promo->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('frontend.promotion_details', $promo->id),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($promo->created_at),
            ]);
        }

        $recentToursQuery = Tour::where('status', 1);
        if ($userCreatedAt) {
            $recentToursQuery->where('created_at', '>=', $userCreatedAt);
        }
        $recentTours = $recentToursQuery->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($recentTours as $t) {
            $key = 'cust_tour_' . $t->id;
            $isUnread = !in_array($key, $readNotifs);

            $notifications->push([
                'id'          => $key,
                'title'       => Str::limit($t->name, 15),
                'description' => Str::limit(strip_tags($t->description ?: 'កន្លែងទេសចរណ៍គួរឱ្យចាប់អារម្មណ៍'), 35),
                'icon'        => 'fas fa-map-marked-alt',
                'icon_bg'     => 'bg-emerald-500/10 text-emerald-500',
                'time'        => $t->created_at ? $t->created_at->locale('km')->diffForHumans() : '',
                'url'         => route('toursdetail', $t->id),
                'is_unread'   => $isUnread,
                'timestamp'   => strtotime($t->created_at),
            ]);
        }

        $sortedNotifs = $notifications->sortByDesc('timestamp')->values();
        $unreadCount = $sortedNotifs->where('is_unread', true)->count();
        $top5Notifs = $sortedNotifs->take(5)->values();

        return response()->json([
            'count'         => $unreadCount,
            'notifications' => $top5Notifs,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false]);
            }
            return back();
        }

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            $userId = Auth::id();
            
            $hotelBookings = DB::table('hotel_bookings')
                ->leftJoin('payments', 'hotel_bookings.id', '=', 'payments.hotel_booking_id')
                ->where('hotel_bookings.user_id', $userId)
                ->select('hotel_bookings.id', 'hotel_bookings.status', 'payments.status as payment_status')
                ->get();

            $meetingBookings = DB::table('meeting_bookings')
                ->leftJoin('payments', 'meeting_bookings.id', '=', 'payments.meeting_booking_id')
                ->where('meeting_bookings.user_id', $userId)
                ->select('meeting_bookings.id', 'meeting_bookings.status', 'payments.status as payment_status')
                ->get();

            $postIds = Post::where('status', 'published')->pluck('id')->map(fn($id) => 'cust_post_' . $id)->toArray();
            $promoIds = Promotion::pluck('id')->map(fn($id) => 'cust_promo_' . $id)->toArray();
            $tourIds = Tour::pluck('id')->map(fn($id) => 'cust_tour_' . $id)->toArray();

            $ids = [];
            foreach ($hotelBookings as $hb) {
                $ids[] = 'cust_hotel_' . $hb->id . '_' . $hb->status . '_' . ($hb->payment_status ?? 'unpaid');
            }
            foreach ($meetingBookings as $mb) {
                $ids[] = 'cust_meeting_' . $mb->id . '_' . $mb->status . '_' . ($mb->payment_status ?? 'unpaid');
            }
            $ids = array_merge($ids, $postIds, $promoIds, $tourIds);
        }

        $readNotifs = session('read_customer_notifications', []);
        $merged = array_unique(array_merge($readNotifs, $ids));
        session(['read_customer_notifications' => $merged]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'បានសន្មតថាអានការជូនដំណឹងទាំងអស់រួចរាល់');
    }
}
