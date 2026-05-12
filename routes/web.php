<?php


use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;

// for customer
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelFrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\RoomWebController;
use App\Http\Controllers\MeetingWebController;
use App\Http\Controllers\AboutWebController;
use App\Http\Controllers\ContactWebController;
use App\Http\Controllers\FacilitiWebController;
use App\Http\Controllers\BookingWebController;
use App\Http\Controllers\ReviewWebController;
use App\Http\Controllers\GalleryWebController;
use App\Http\Controllers\CartController;


// for admin
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
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CalendarController;

// use App\Http\Controllers\Admin\


// Authentication Routes
Route::controller(AuthWebController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');
});

Route::post('/change-language', function (Illuminate\Http\Request $request) {
    $locale = $request->locale;
    if (in_array($locale, ['en', 'kh'])) {
        session()->put('locale', $locale);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 400);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');



// =======================================
// Frontend Website Routes
// =======================================
// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/facilities', [FacilitiWebController::class, 'index'])->name('frontend.facilities');
Route::get('/about', [AboutWebController::class, 'index'])->name('frontend.about');
Route::get('/contact', [ContactWebController::class, 'index'])->name('frontend.contact');
Route::post('/contact', [ContactWebController::class, 'store'])->name('frontend.contact.store');


// Hotels & Rooms
// Route::get('/details', [HotelFrontendController::class, 'index'])->name('frontend.hotels.index');
Route::get('/roomdetails/{id}', [HomeController::class, 'room_detail'])->name('frontend.room_details'); // Room details
Route::get('/meetingdetails/{id}', [HomeController::class, 'meeting_detail'])->name('frontend.meeting_details'); // Meeting details
Route::get('/rooms', [RoomWebController::class, 'index'])->name('frontend.rooms');
Route::get('/room-type/{id}', [HomeController::class, 'roomTypeDetails'])->name('frontend.room_type'); // Room type details
Route::get('/gallery', [GalleryWebController::class, 'index'])->name('frontend.gallery');

// Meeting Page
Route::get('/meeting', [MeetingWebController::class, 'index'])->name('frontend.meeting');
Route::get('/meeting/detail/{id}', [MeetingWebController::class, 'meeting_detail'])->name('frontend.meeting_detail');


Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('cart.count');

// Booking Routes
Route::get('/booking', [BookingWebController::class, 'index'])->name('booking.index');
Route::post('/booking/store', [BookingWebController::class, 'store'])->name('booking.store')->middleware('auth');

Route::post('/bookings/store', [RoomWebController::class, 'storeBooking'])->name('frontend.bookings.store')->middleware('auth');
Route::post('/booking/storecart', [BookingWebController::class, 'storecart'])->name('booking.storecart');
Route::get('/booking/history', [BookingWebController::class, 'history'])->name('booking.history');
Route::get('/booking/details/{id}', [BookingWebController::class, 'show'])->name('booking.show');
Route::get('/show/{id}', [BookingWebController::class, 'show'])->name('show');
Route::get('/success/{id}', [BookingWebController::class, 'success'])->name('success');

// Checkout & Payment
Route::get('/booking/{id}/checkout', [BookingWebController::class, 'checkout'])->name('booking.checkout');
Route::post('/booking/{id}/payment', [BookingWebController::class, 'processPayment'])->name('booking.payment');

// Reviews
Route::post('/reviews', [ReviewWebController::class, 'store'])->name('reviews.store');

// Success
Route::get('/booking/success/{id}', [BookingWebController::class, 'success'])
    ->name('booking.success');


Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

// ផ្នែក Admin (Backend)
// ២. Protected Routes (តម្រូវឱ្យ Login រួចរាល់)
Route::middleware(['auth'])->group(function () {

    // សម្រាប់ Admin និង Staff (មើល Dashboard និង CRUD ទូទៅ)
    Route::middleware(['role:admin,staff'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // CRUD សម្រាប់ Hotels និង Room Types

    });

    // សម្រាប់តែ Admin ប៉ុណ្ណោះ (សិទ្ធិខ្ពស់បំផុត)
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // --- Core Resources ---
        // Route::resource('calendar', CalendarController::class);
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
