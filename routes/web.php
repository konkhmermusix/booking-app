<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;

// For Customer
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingWebController;
use App\Http\Controllers\RoomWebController;
use App\Http\Controllers\MeetingWebController;
use App\Http\Controllers\AboutWebController;
use App\Http\Controllers\ContactWebController;
use App\Http\Controllers\FacilitiWebController;
use App\Http\Controllers\BookingWebController;
use App\Http\Controllers\GalleryWebController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ChatWebController;
use App\Http\Controllers\PostWebController;
use App\Http\Controllers\PolicyWebController;
use App\Http\Controllers\CustomerNotificationController;
use App\Http\Controllers\Admin\GlobalSearchController;

// For Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SlideshowController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ReportRoomController;
use App\Http\Controllers\Admin\ReportMeetingController;
use App\Http\Controllers\Admin\ReportRevenueController;
use App\Http\Controllers\Admin\ReportCustomerController;
use App\Http\Controllers\Admin\ReportPaymentController;
use App\Http\Controllers\Admin\ReportRoomStatusController;
use App\Http\Controllers\Admin\RoomBookingController;
use App\Http\Controllers\Admin\MeetingBookingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\NotificationController;

// Authentication Routes
Route::controller(AuthWebController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post')->middleware('throttle:5,1');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post')->middleware('throttle:5,1');
    Route::get('auth/google', [AuthWebController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthWebController::class, 'handleGoogleCallback']);
    Route::get('auth/facebook', [AuthWebController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('auth/facebook/callback', [AuthWebController::class, 'handleFacebookCallback']);

    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update')->middleware('throttle:5,1');
});


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');



// Start Frontend Website Routes
// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hotels', [HomeController::class, 'hotels'])->name('frontend.hotels');
Route::get('/tours', [HomeController::class, 'tours'])->name('frontend.tours');
Route::get('/toursdetail/{id}', [HomeController::class, 'toursdetail'])->name('toursdetail');
Route::get('/promotions/{id}', [HomeController::class, 'promotion_detail'])->name('frontend.promotion_details');
Route::post('/promotion/cart/addhotel', [CartController::class, 'addHotelPromo'])->name('promotion.addhotelpro');
Route::post('/promotion/cart/addmeeting', [CartController::class, 'addMeetingPromo'])->name('promotion.addmeetingpro');
Route::get('/gallery', [GalleryWebController::class, 'index'])->name('frontend.gallery');


// For Stay Room 
Route::get('/rooms', [RoomWebController::class, 'index'])->name('frontend.rooms');
Route::get('/rooms/check-availability', [RoomWebController::class, 'checkAvailability'])->name('frontend.rooms.check_availability');
Route::get('/roomdetails/{id}', [RoomWebController::class, 'room_detail'])->name('frontend.room_details');

// For Meeting Room
Route::get('/meeting', [MeetingWebController::class, 'index'])->name('frontend.meeting');
Route::get('/meeting/check-availability', [MeetingWebController::class, 'checkAvailability'])->name('frontend.meeting.check_availability');
Route::get('/meetingdetails/{id}', [MeetingWebController::class, 'meeting_detail'])->name('frontend.meeting_details');

// Authenticated Review & Reply Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/roomdetails/{id}', [RoomWebController::class, 'storeReview'])->name('frontend.room_details.store');
    Route::post('/roomdetails/{id}/reply', [RoomWebController::class, 'storeReply'])->name('frontend.room_details.replay');
    Route::put('/roomdetails/{id}', [RoomWebController::class, 'updateReview'])->name('frontend.room_details.update');
    Route::delete('/roomdetails/{id}', [RoomWebController::class, 'deleteReview'])->name('frontend.room_details.delete');

    Route::post('/meetingdetails/{id}', [MeetingWebController::class, 'storeReview'])->name('frontend.meeting_details.store');
    Route::post('/meetingdetails/{id}/reply', [MeetingWebController::class, 'storeReply'])->name('frontend.meeting_details.replay');
    Route::put('/meetingdetails/{id}', [MeetingWebController::class, 'updateReview'])->name('frontend.meeting_details.update');
    Route::delete('/meetingdetails/{id}', [MeetingWebController::class, 'deleteReview'])->name('frontend.meeting_details.delete');

    // Customer Notifications
    Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/customer/notifications', [CustomerNotificationController::class, 'getNotifications'])->name('customer.notifications');
    Route::post('/customer/notifications/mark-read', [CustomerNotificationController::class, 'markAllAsRead'])->name('customer.notifications.mark-read');
});

// For Post
Route::get('/posts', [PostWebController::class, 'index'])->name('frontend.posts');
Route::get('/posts_details/{id}', [PostWebController::class, 'post_detail'])->name('frontend.posts_detail');

// For Service
Route::get('/facilities', [FacilitiWebController::class, 'index'])->name('frontend.facilities');

// For About
Route::get('/about', [AboutWebController::class, 'index'])->name('frontend.about');

// For Contact Us
Route::get('/contact', [ContactWebController::class, 'index'])->name('frontend.contact');
Route::post('/contact', [ContactWebController::class, 'store'])->name('frontend.contact_post')->middleware('throttle:3,1');

// Cart Page
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::post('/cart/add/hotel', [CartController::class, 'addHotel'])->name('cart.add.hotel');
Route::post('/cart/add/meeting', [CartController::class, 'addMeeting'])->name('cart.add.meeting');

Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('/update-dates', [CartController::class, 'updateDates'])->name('cart.update-dates');
Route::delete('/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// History
Route::get('/mybookings', [BookingWebController::class, 'myBookings'])->name('mybookings');
Route::get('/booking/receipt/{code}', [BookingWebController::class, 'viewReceipt'])->name('receipt');
Route::get('/booking/pdf/{code}', [BookingWebController::class, 'downloadPdf'])->name('receipt.pdf');
Route::post('/mybookings/{id}/cancel', [BookingWebController::class, 'cancelBooking'])->name('bookings.cancel')->middleware('auth');

// Checkout Page
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// Checkout & Payment
Route::get('/booking/{id}/checkout', [BookingWebController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/{id}/payment', [BookingWebController::class, 'processPayment'])->name('booking.payment');

// Success Page
Route::get('/booking/success/{code}', [BookingWebController::class, 'bookingSuccess'])
    ->name('booking.success');

// Setting (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/setting', [SettingWebController::class, 'edit'])->name('setting.edit');
    Route::put('/setting', [SettingWebController::class, 'update'])->name('setting.update');
    Route::put('/setting/password', [SettingWebController::class, 'updatePassword'])->name('setting.password.update');
    Route::delete('/setting', [SettingWebController::class, 'destroy'])->name('setting.destroy');
});

Route::get('/terms-and-conditions', [PolicyWebController::class, 'terms'])->name('policies.terms');
Route::get('/privacy-policy', [PolicyWebController::class, 'privacy'])->name('policies.privacy');
Route::get('/customer-reviews', [PolicyWebController::class, 'reviews'])->name('policies.reviews');

// End Frontend Website Routes

// Start Admin Routes
// Protected Routes
Route::middleware(['auth'])->group(function () {


    Route::get('/chat', [ChatWebController::class, 'index'])->name('chat.index');
    Route::get('/chat/fetch/{id}', [ChatWebController::class, 'fetchMessages'])->name('chat.fetch');
    Route::post('/chat/send', [ChatWebController::class, 'sendMessage'])->name('chat.send');
    Route::put('/chat/messages/{id}', [ChatWebController::class, 'updateMessage'])->name('messages.update');
    Route::delete('/chat/messages/{id}', [ChatWebController::class, 'destroyMessage'])->name('messages.destroy');

    // For Admin and Staff
    Route::middleware(['role:admin,staff'])->prefix('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications-page', [NotificationController::class, 'index'])->name('admin.notifications.index');
        Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('admin.notifications');
        Route::get('/notifications/read/{type}/{id}', [NotificationController::class, 'readAndRedirect'])->name('admin.notifications.read');
        Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-read');
        Route::get('/global-search', [GlobalSearchController::class, 'index'])->name('admin.global-search');
        Route::post('/bookings/{id}/approve', [DashboardController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [DashboardController::class, 'reject'])->name('admin.bookings.reject');

        Route::get('/reportrooms-export-excel', [ReportRoomController::class, 'exportExcel'])->name('reportrooms.export-excel');
        Route::get('/reportrooms-export-pdf', [ReportRoomController::class, 'exportPdf'])->name('reportrooms.export-pdf');

        Route::get('/reportmeetings-export-excel', [ReportMeetingController::class, 'exportExcel'])->name('reportmeetings.export-excel');
        Route::get('/reportmeetings-export-pdf', [ReportMeetingController::class, 'exportPdf'])->name('reportmeetings.export-pdf');

        Route::get('/reportsrevenue-export-excel', [ReportRevenueController::class, 'exportExcel'])->name('reportsrevenue.export-excel');
        Route::get('/reportsrevenue-export-pdf', [ReportRevenueController::class, 'exportPdf'])->name('reportsrevenue.export-pdf');

        Route::get('/reportpayments-export-excel', [ReportPaymentController::class, 'exportExcel'])->name('reportpayments.export-excel');
        Route::get('/reportpayments-export-pdf', [ReportPaymentController::class, 'exportPdf'])->name('reportpayments.export-pdf');

        Route::get('/reportcustomers-export-excel', [ReportCustomerController::class, 'exportExcel'])->name('reportcustomers.export-excel');
        Route::get('/reportcustomers-export-pdf', [ReportCustomerController::class, 'exportPdf'])->name('reportcustomers.export-pdf');

        Route::get('/reportroomstatus-export-excel', [ReportRoomStatusController::class, 'exportExcel'])->name('reportroomstatus.export-excel');
        Route::get('/reportroomstatus-export-pdf', [ReportRoomStatusController::class, 'exportPdf'])->name('reportroomstatus.export-pdf');

        Route::resource('reportrooms', ReportRoomController::class);
        Route::resource('reportmeetings', ReportMeetingController::class);
        Route::resource('reportsrevenue', ReportRevenueController::class);
        Route::resource('reportpayments', ReportPaymentController::class);
        Route::resource('reportcustomers', ReportCustomerController::class);
        Route::resource('reportroomstatus', ReportRoomStatusController::class);

        Route::get('room-bookings/available-rooms', [RoomBookingController::class, 'getAvailableRooms'])->name('room-bookings.available-rooms');
        Route::get('meeting-bookings/available-rooms', [MeetingBookingController::class, 'getAvailableRooms'])->name('meeting-bookings.available-rooms');
        Route::get('room-bookings/{id}/print-invoice', [RoomBookingController::class, 'printInvoice'])->name('room-bookings.print-invoice');
        Route::get('meeting-bookings/{id}/print-invoice', [MeetingBookingController::class, 'printInvoice'])->name('meeting-bookings.print-invoice');

        Route::resource('room-bookings', RoomBookingController::class);
        Route::resource('meeting-bookings', MeetingBookingController::class);

        // Core Booking & Room Management for Admin & Staff
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
        Route::post('/calendar/update-status/{id}', [CalendarController::class, 'updateStatus'])->name('calendar.update-status');

        Route::resource('bookings', BookingController::class);
        Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::get('bookings/invoice/{booking}', [BookingController::class, 'downloadInvoice'])->name('bookings.invoice');

        Route::resource('hotels', HotelController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('room_types', RoomTypeController::class);
        Route::delete('room_types/images/{id}', [RoomTypeController::class, 'destroyImage']);
        Route::resource('facilities', FacilityController::class);

        Route::resource('posts', PostController::class)->except(['create', 'edit', 'show']);
        Route::post('posts/{post}/images/delete', [PostController::class, 'destroyImage'])->name('posts.images.destroy');

        // Admin & Staff Chat / Messages (handled by ChatController)
        Route::get('/messages', [ChatController::class, 'index'])->name('messages.index');
        Route::get('/messages/{conversation}', [ChatController::class, 'show'])->name('messages.show');
        Route::post('/messages/{conversation}', [ChatController::class, 'store'])->name('messages.store');
        Route::delete('/conversations/{id}', [ChatController::class, 'destroyConversation'])->name('conversations.destroy');
    });

    // For Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // System & Master Data Resources
        Route::resource('users', UserController::class);
        Route::resource('slideshows', SlideshowController::class);
        Route::resource('tours', TourController::class);
        Route::resource('promotions', PromotionController::class);

        // Contacts
        Route::resource('contact', ContactController::class);
        Route::resource('reviews', ReviewController::class);
        Route::resource('contacts_sett', ContactSettingController::class);

        // Gallery
        Route::resource('galleries', GalleryController::class);

        // about
        Route::resource('abouts', AboutController::class);

        // About Content
        Route::post('/about/store', [AboutController::class, 'storeAbout'])->name('about.store');
        Route::post('/about/update/{id}', [AboutController::class, 'updateAbout'])->name('about.update');
        Route::delete('/about/delete/{id}', [AboutController::class, 'destroyAbout'])->name('about.destroy');

        // History
        Route::post('/history/store', [AboutController::class, 'storeHistory'])->name('history.store');
        Route::post('/history/update/{id}', [AboutController::class, 'updateHistory'])->name('history.update');
        Route::delete('/history/delete/{id}', [AboutController::class, 'destroyHistory'])->name('history.destroy');

        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');
    });
});
