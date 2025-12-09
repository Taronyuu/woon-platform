<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ImageProxyController;
use App\Http\Controllers\MortgageCalculatorController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::get('/img/{token}', [ImageProxyController::class, 'show'])
    ->name('image.proxy')
    ->where('token', '.*');

Route::get('/', function () {
    return view('oxxen');
})->name('oxxen');

Route::post('/notify', function () {
    request()->validate(['email' => 'required|email|unique:contact_list,email']);
    \App\Models\ContactList::query()->create(['email' => request('email')]);
    return back()->with('success', true);
})->name('notify.store')->middleware('throttle:5,1');

Route::get('/home', function () {
    $featuredProperties = \App\Models\PropertyUnit::query()
        ->where('status', 'available')
        ->latest()
        ->limit(6)
        ->get();

    return view('index', ['featuredProperties' => $featuredProperties]);
})->name('home');

Route::get('/maandlasten', [MortgageCalculatorController::class, 'index'])->name('mortgage.calculator');
Route::get('/api/interest-rate', [MortgageCalculatorController::class, 'getInterestRate'])->name('api.interest-rate');

Route::get('/api/cities', [CityController::class, 'index']);

Route::get('/zoeken', [PropertyController::class, 'index'])->name('search');

Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/voorwaarden', [PageController::class, 'terms'])->name('terms');
Route::get('/over-oxxen', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/woningen/{property:slug}', [PropertyController::class, 'show'])->name('property.show');

Route::post('/woningen/{property:slug}/favorite', [PropertyController::class, 'addToFavorites'])
    ->name('property.favorite')
    ->middleware('auth');
Route::delete('/woningen/{property:slug}/favorite', [PropertyController::class, 'removeFromFavorites'])
    ->name('property.unfavorite')
    ->middleware('auth');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:5,1');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');

Route::post('/logout', LogoutController::class)->name('logout');

Route::get('/wachtwoord-vergeten', [ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('/wachtwoord-vergeten', [ForgotPasswordController::class, 'store'])->name('password.email')->middleware('throttle:3,1');
Route::get('/wachtwoord-reset/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
Route::post('/wachtwoord-reset', [ResetPasswordController::class, 'store'])->name('password.update')->middleware('throttle:5,1');

Route::get('/email/verificatie', [EmailVerificationController::class, 'notice'])->name('verification.notice')->middleware('auth');
Route::post('/email/verificatie/opnieuw', [EmailVerificationController::class, 'resend'])->name('verification.send')->middleware('auth');
Route::get('/email/verificatie/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('signed');

Route::get('/account', function () {
    $user = auth()->user();
    $favorites = $user->favoriteProperties()->get();
    $searchProfiles = $user->searchProfiles()->latest()->get();
    return view('account-consument', [
        'favorites' => $favorites,
        'searchProfiles' => $searchProfiles,
    ]);
})->name('account.consumer')->middleware('auth');

Route::post('/account/profile', [App\Http\Controllers\ProfileController::class, 'update'])
    ->name('profile.update')
    ->middleware('auth');

Route::post('/account/notifications', [App\Http\Controllers\ProfileController::class, 'updateNotifications'])
    ->name('profile.notifications.update')
    ->middleware('auth');

Route::post('/account/zoekprofielen', [App\Http\Controllers\SearchProfileController::class, 'store'])
    ->name('search-profiles.store')
    ->middleware('auth');
Route::put('/account/zoekprofielen/{searchProfile}', [App\Http\Controllers\SearchProfileController::class, 'update'])
    ->name('search-profiles.update')
    ->middleware('auth');
Route::delete('/account/zoekprofielen/{searchProfile}', [App\Http\Controllers\SearchProfileController::class, 'destroy'])
    ->name('search-profiles.destroy')
    ->middleware('auth');
Route::patch('/account/zoekprofielen/{searchProfile}/toggle', [App\Http\Controllers\SearchProfileController::class, 'toggleActive'])
    ->name('search-profiles.toggle')
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
