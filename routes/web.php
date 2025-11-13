<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MortgageCalculatorController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProperties = \App\Models\PropertyUnit::where('status', 'available')
        ->latest()
        ->limit(6)
        ->get();

    return view('index', ['featuredProperties' => $featuredProperties]);
})->name('home');

Route::get('/maandlasten', [MortgageCalculatorController::class, 'index'])->name('mortgage.calculator');
Route::get('/api/interest-rate', [MortgageCalculatorController::class, 'getInterestRate'])->name('api.interest-rate');

Route::get('/api/cities', [CityController::class, 'index']);

Route::get('/zoeken', [PropertyController::class, 'index'])->name('search');

Route::get('/woningen/{property:slug}', [PropertyController::class, 'show'])->name('property.show');

Route::post('/woningen/{property:slug}/favorite', [PropertyController::class, 'addToFavorites'])
    ->name('property.favorite')
    ->middleware('auth');
Route::delete('/woningen/{property:slug}/favorite', [PropertyController::class, 'removeFromFavorites'])
    ->name('property.unfavorite')
    ->middleware('auth');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/account', function () {
    $favorites = auth()->user()->favoriteProperties()->get();
    return view('account-consument', ['favorites' => $favorites]);
})->name('account.consumer')->middleware('auth');

Route::post('/account/profile', [App\Http\Controllers\ProfileController::class, 'update'])
    ->name('profile.update')
    ->middleware('auth');

Route::post('/account/notifications', [App\Http\Controllers\ProfileController::class, 'updateNotifications'])
    ->name('profile.notifications.update')
    ->middleware('auth');

Route::prefix('makelaar')->name('realtor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('account-makelaar');
    })->name('dashboard');

    Route::get('/woningen/toevoegen', function () {
        return view('woning-invoeren');
    })->name('properties.create');

    Route::get('/berichten', function () {
        return view('berichten');
    })->name('messages');

    Route::get('/relaties', function () {
        return view('crm-relaties');
    })->name('crm');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin-dashboard');
    })->name('dashboard');

    Route::get('/api-beheer', function () {
        return view('admin-api-beheer');
    })->name('api');

    Route::get('/scraping', function () {
        return view('admin-scraping');
    })->name('scraping');
});
