<?php

use App\Http\Controllers\Backoffice\StoreController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// TODO: envoyer dans auth + verified middleware avant la fin du dev.
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

// TODO: restore auth and admin middleware when backoffice access control is activated.
Route::prefix('backoffice')
    ->name('backoffice.')
    ->group(function () {

        Route::put(
            '/stores/{store}',
            [StoreController::class, 'update']
        )->name('stores.update');

        Route::get(
            '/stores',
            [StoreController::class, 'index']
        )->name('stores.index');

        Route::post(
            '/stores',
            [StoreController::class, 'store']
        )->name('stores.store');

        Route::get(
            '/stores/{store}',
            [StoreController::class, 'show']
        )->name('stores.show');
    });

require __DIR__ . '/auth.php';
