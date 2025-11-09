<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/zoekresultaten', function () {
    return view('zoekresultaten');
})->name('search');

Route::get('/woningen/{id}', function ($id) {
    return view('detailpagina');
})->name('property.show');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/account', function () {
    return view('account-consument');
})->name('account.consumer')->middleware('auth');

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
