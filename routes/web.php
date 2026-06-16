<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;

// For Customer
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
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

// For Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SlideshowController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\Dashboard1Controller;

// Authentication Routes
Route::controller(AuthWebController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');
    Route::get('auth/google', [AuthWebController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthWebController::class, 'handleGoogleCallback']);
    Route::get('auth/facebook', [AuthWebController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('auth/facebook/callback', [AuthWebController::class, 'handleFacebookCallback']);

    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
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
Route::post('/chat/send', [ChatWebController::class, 'store'])->middleware('auth');
Route::get('/toursdetail/{id}', [HomeController::class, 'toursdetail'])->name('toursdetail');
Route::get('/promotions/{id}', [HomeController::class, 'promotion_detail'])->name('frontend.promotion_details');
Route::post('/promotion/cart/addhotel', [CartController::class, 'addHotelPromo'])->name('promotion.addhotelpro');
Route::post('/promotion/cart/addmeeting', [CartController::class, 'addMeetingPromo'])->name('promotion.addmeetingpro');
Route::get('/gallery', [GalleryWebController::class, 'index'])->name('frontend.gallery');

// Route::post('/cart/add/hotelpromo', [CartController::class, 'addHotelPromo'])->name('cart.addhotel');
// Route::post('/cart/add/meetingpromo', [CartController::class, 'addMeetingPromo'])->name('cart.addmeeting');

// For Stay Room 
Route::get('/rooms', [RoomWebController::class, 'index'])->name('frontend.rooms');
Route::get('/roomdetails/{id}', [RoomWebController::class, 'room_detail'])->name('frontend.room_details');
Route::post('/roomdetails/{id}', [RoomWebController::class, 'storeReview'])->name('frontend.room_details.store');
Route::post('/roomdetails/{id}/reply', [RoomWebController::class, 'storeReply'])->name('frontend.room_details.replay');
Route::put('/roomdetails/{id}', [RoomWebController::class, 'updateReview'])->name('frontend.room_details.update');
Route::delete('/roomdetails/{id}', [RoomWebController::class, 'deleteReview'])->name('frontend.room_details.delete');

// For Meeting Room
Route::get('/meeting', [MeetingWebController::class, 'index'])->name('frontend.meeting');
Route::get('/meetingdetails/{id}', [MeetingWebController::class, 'meeting_detail'])->name('frontend.meeting_details');
Route::post('/meetingdetails/{id}', [MeetingWebController::class, 'storeReview'])->name('frontend.meeting_details.store');
Route::post('/meetingdetails/{id}/reply', [MeetingWebController::class, 'storeReply'])->name('frontend.meeting_details.replay');
Route::put('/meetingdetails/{id}', [MeetingWebController::class, 'updateReview'])->name('frontend.meeting_details.update');
Route::delete('/meetingdetails/{id}', [MeetingWebController::class, 'deleteReview'])->name('frontend.meeting_details.delete');

// For Service
Route::get('/facilities', [FacilitiWebController::class, 'index'])->name('frontend.facilities');

// For About
Route::get('/about', [AboutWebController::class, 'index'])->name('frontend.about');

// For Contact Us
Route::get('/contact', [ContactWebController::class, 'index'])->name('frontend.contact');
Route::post('/contact', [ContactWebController::class, 'store'])->name('frontend.contact');

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


// Checkout Page
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');


// Checkout & Payment
Route::get('/booking/{id}/checkout', [BookingWebController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/{id}/payment', [BookingWebController::class, 'processPayment'])->name('booking.payment');

// Success
Route::get('/booking/success/{id}', [BookingWebController::class, 'success'])
    ->name('booking.success');

// Setting
Route::get('/setting', [SettingController::class, 'edit'])->name('setting.edit');
Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
Route::put('/setting/password', [SettingController::class, 'updatePassword'])->name('setting.password.update');
Route::delete('/setting', [SettingController::class, 'destroy'])->name('setting.destroy');
// End Frontend Website Routes

// Start Admin Routes
// Protected Routes
Route::middleware(['auth'])->group(function () {

    // For Admin and Staff
    Route::middleware(['role:admin,staff'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard1', [Dashboard1Controller::class, 'index'])->name('dashboard1');
        Route::post('/bookings/{id}/approve', [DashboardController::class, 'approve'])->name('admin.bookings.approve');
        Route::post('/bookings/{id}/reject', [DashboardController::class, 'reject'])->name('admin.bookings.reject');
    });

    // For Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Core Resources
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
        Route::resource('users', UserController::class);
        Route::resource('hotels', HotelController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('room_types', RoomTypeController::class);
        Route::delete('room_types/images/{id}', [RoomTypeController::class, 'destroyImage']);
        Route::resource('facilities', FacilityController::class);
        Route::resource('slideshows', SlideshowController::class);
        Route::resource('tours', TourController::class);
        Route::resource('promotions', PromotionController::class);
        Route::resource('bookings', BookingController::class);
        Route::resource('bookings', BookingController::class);
        Route::get('bookings/invoice/{booking}', [BookingController::class, 'downloadInvoice'])->name('bookings.invoice');

        Route::post('/chat/send', [ChatController::class, 'store'])->name('admin.chat.send');

        // Contacts
        Route::resource('contact', ContactController::class);
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
