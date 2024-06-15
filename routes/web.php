<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('catalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('catalog/create', [CatalogController::class, 'create'])->name('catalog.create');
Route::get('catalog/update', [CatalogController::class, 'update'])->name('catalog.update');
Route::get('catalog/delete', [CatalogController::class, 'delete'])->name('catalog.delete');
Route::get('catalog/update_or_create', [CatalogController::class, 'updateOrCreate'])->name('catalog.update_or_create');

Route::middleware(['auth', 'verified'])->group(function (){
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});


Route::middleware('guest')->group(function (){
    Route::get('register', [UserController::class, 'create'])->name('register');
    Route::post('register', [UserController::class, 'store'])->name('user.store');
    Route::get('login', [UserController::class, 'login'])->name('login');
    Route::post('login', [UserController::class, 'loginAuth'])->name('login.auth');
});



Route::middleware('auth')->group(function (){
    Route::get('verify-email', function () {
        return view('user.verify-email');
    })->name('verification.notice'); # за уведомление

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware('signed')->name('verification.verify'); # за то что пользователь зареган

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:3,1')->name('verification.send'); # за повторную отправку

    Route::get('logout', [UserController::class, 'logout'])->name('logout');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin',function (){
        dump("123");
    })->name('admin');
});


