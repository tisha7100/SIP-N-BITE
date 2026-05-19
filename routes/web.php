<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers as Customer;
use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\DeliveryPartner\DashboardController;

/*
|--------------------------------------------------------------------------
| GLOBAL LOGOUT (ONLY ONE - IMPORTANT)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [Customer\HomeController::class, 'index'])->name('home');

Route::get('/menu', [Customer\HomeController::class, 'menu'])->name('menu');

Route::get('/dining', function () {
    return view('dining');
})->name('dining');

Route::get('/about', function () {
    return view('about');
})->name('about');

// --- REPLACE OLD STATIC ENTRY WITH THESE TWO DYNAMIC CONTACT ROUTES ---
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
// ----------------------------------------------------------------------

Route::get('/menu/{id}', [Customer\HomeController::class, 'foodDetail'])
    ->name('food.detail');

Route::get('/book-table', [Customer\TableBookingController::class, 'index'])
    ->name('booking.index');


/*
|--------------------------------------------------------------------------
| AUTH (LOGIN / REGISTER)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // LOGIN
    Route::get('/login', [Customer\AuthController::class, 'showLogin'])
        ->name('login');

    Route::get('/customer/login', [Customer\AuthController::class, 'showLogin'])
        ->name('customer.login');

    Route::post('/login', [Customer\AuthController::class, 'login'])
        ->name('login.post');

    // REGISTER
    Route::get('/register', [Customer\AuthController::class, 'showRegister'])
        ->name('register');

    Route::get('/customer/register', [Customer\AuthController::class, 'showRegister'])
        ->name('customer.register');

    Route::post('/register', [Customer\AuthController::class, 'register'])
        ->name('register.post');

    // GOOGLE LOGIN
    Route::get('auth/google', [Customer\AuthController::class, 'redirectToGoogle'])
        ->name('google.login');

    Route::get('auth/google/callback', [Customer\AuthController::class, 'handleGoogleCallback']);
});


/*
|--------------------------------------------------------------------------
| CUSTOMER AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/cart', [Customer\CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add', [Customer\CartController::class, 'add'])
        ->name('cart.add');

    Route::post('/menu/{id}/review', [Customer\HomeController::class, 'submitReview'])
        ->name('menu.review');

    Route::get('/cart/remove/{id}', [Customer\CartController::class, 'remove'])
        ->name('cart.remove');

    Route::get('/checkout', [Customer\CheckoutController::class, 'index'])
        ->name('checkout');

    Route::post('/check-distance', [Customer\CheckoutController::class, 'checkDistance'])
        ->name('checkout.distance');

    Route::post('/checkout', [Customer\CheckoutController::class, 'process'])
        ->name('checkout.process');

    Route::get('/my-orders', [Customer\OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/order/{id}', [Customer\OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/order/{id}/bill', [Customer\OrderController::class, 'bill'])
        ->name('orders.bill');

    Route::post('/book-table', [Customer\TableBookingController::class, 'store'])
        ->name('booking.store');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('guest')
    ->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'showLogin'])
            ->name('login');

        Route::post('/login', [Admin\AuthController::class, 'login'])
            ->name('login.post');
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Orders
        Route::get('/orders', [Admin\OrderController::class, 'index'])
            ->name('orders.index');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])
            ->name('orders.show');
        Route::match(['post', 'put', 'patch'], '/orders/{order}', [Admin\OrderController::class, 'update'])
            ->name('orders.update');

        // Categories  <-- THE MISSING ONE
        Route::resource('categories', Admin\CategoryController::class);

        Route::resource('menu', Admin\MenuController::class)
            ->except(['show']);

        Route::get('/customers', [Admin\CustomerController::class, 'index'])
            ->name('customers.index');
        Route::post('/customers/{customer}', [Admin\CustomerController::class, 'update'])
            ->name('customers.update');
        Route::delete('/customers/{customer}', [Admin\CustomerController::class, 'destroy'])
            ->name('customers.destroy');

        Route::get('/tables', [Admin\TableController::class, 'index'])
            ->name('tables.index');
        Route::post('/tables', [Admin\TableController::class, 'store'])
            ->name('tables.store');
        Route::delete('/tables/{table}', [Admin\TableController::class, 'destroy'])
            ->name('tables.destroy');

        Route::match(['post', 'put', 'patch'], '/bookings/{booking}', [AdminBookingController::class, 'update'])
            ->name('bookings.update');

        Route::get('/orders/{order}/bill', [Admin\BillController::class, 'generate'])
            ->name('bill.generate');

        // Add these too if your sidebar references them (open the blade to confirm)
        // Route::resource('products', Admin\ProductController::class);
        // Route::resource('users', Admin\UserController::class);
        // Route::resource('tables', Admin\TableController::class);
        // Route::resource('bookings', Admin\BookingController::class);

        // Admin logout
        Route::post('/logout', function () {
            Auth::guard('admin')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/login');
        })->name('logout');
    });
/*
|--------------------------------------------------------------------------
| DELIVERY PARTNER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('delivery-partner')
    ->name('delivery-partner.')
    ->group(function () {

        Route::middleware('guest:delivery_partner')->group(function () {

            Route::get('/login', [DashboardController::class, 'showLogin'])
                ->name('login');

            Route::post('/login', [DashboardController::class, 'login'])
                ->name('login.post');
        });

        Route::middleware('auth:delivery_partner')->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/profile', [DashboardController::class, 'profile'])
                ->name('profile');

            Route::get('/all-partners', [DashboardController::class, 'allPartners'])
                ->name('all-partners');

            Route::get('/partners/{partner}', [DashboardController::class, 'show'])
                ->name('show');

            Route::post('/partners/{partner}/toggle-status', [DashboardController::class, 'toggleStatus'])
                ->name('toggle-status');

            Route::match(['post', 'put', 'patch'], '/orders/{order}', [DashboardController::class, 'updateOrderStatus'])
                ->name('orders.update');

            Route::post('/logout', function () {
                Auth::guard('delivery_partner')->logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('delivery-partner.login');
            })->name('logout');
        });
    });
