<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// TODO: move this route into auth + verified middleware when checkout requires customer account.
Route::get('/checkout/redirect', function () {
    return Inertia::render('Checkout/Redirect');
})->name('checkout.redirect');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Customer/Dashboard');
    })->name('dashboard');

    Route::get('/customer/orders', function () {
        return Inertia::render('Customer/Order/Index');
    })->name('customer.orders.index');

    Route::get('/customer/orders/{order}', function (int $order) {
        return Inertia::render('Customer/Order/Show', [
            'orderId' => $order,
        ]);
    })->name('customer.orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::middleware(['auth'])
    ->prefix('backoffice')
    ->name('backoffice.')
    ->group(function () {
        Route::get('/stores', function () {
            return Inertia::render('Backoffice/StoreIndex');
        })->name('stores.index');

        Route::get('/stores/{store}/stock', function (string $store) {
            return Inertia::render('Backoffice/StoreStock', [
                'storeId' => $store,
            ]);
        })->name('stores.stock');
    });

require __DIR__ . '/auth.php';