<?php

use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminFilterController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('catalogs', [CatalogController::class, 'index'])->name('catalogs');
Route::get('catalog', [CatalogController::class, 'show'])->name('catalog.show');
//Route::get('catalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');

//Route::get('catalog/create', [CatalogController::class, 'create'])->name('catalog.create');
//Route::get('catalog/update', [CatalogController::class, 'update'])->name('catalog.update');
//Route::get('catalog/delete', [CatalogController::class, 'delete'])->name('catalog.delete');
//Route::get('catalog/update_or_create', [CatalogController::class, 'updateOrCreate'])->name('catalog.update_or_create');

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
    Route::get('admin', [AdminController::class, 'index'])->name('admin.index');


    Route::get('admin/catalog', [AdminCatalogController::class, 'index'])->name('admin.catalog.index');
    Route::post('admin/catalog', [AdminCatalogController::class, 'store'])->name('admin.catalog.store');

    Route::get('admin/catalog/{id}/edit', [AdminCatalogController::class, 'edit'])->name('admin.catalog.edit');
    Route::put('admin/catalog/{id}', [AdminCatalogController::class, 'update'])->name('admin.catalog.update');
    Route::post('admin/catalog/{id}/addSubcatalog', [AdminCatalogController::class, 'addSubcatalog'])->name('admin.catalog.addSubcatalog');
    Route::post('admin/catalog/{id}/addFilter', [AdminCatalogController::class, 'addFilter'])->name('admin.catalog.addFilter');

    Route::delete('admin/catalog/{id}/removeImage', [AdminCatalogController::class, 'removeImage'])->name('admin.catalog.removeImage');
    Route::delete('admin/catalog/{id}', [AdminCatalogController::class, 'destroy'])->name('admin.catalog.destroy');


    Route::get('admin/filter/{id}/edit', [AdminFilterController::class, 'edit'])->name('admin.filter.edit');
    Route::put('admin/filter/{id}', [AdminFilterController::class, 'update'])->name('admin.filter.update');
    Route::delete('admin/filter/{id}', [AdminFilterController::class, 'destroy'])->name('admin.filter.destroy');

    Route::post('admin/filter/{id}/addSubfilter', [AdminFilterController::class, 'addSubfilter'])->name('admin.filter.addSubfilter');
    Route::post('admin/filter/{id}/addValue', [AdminFilterController::class, 'addValue'])->name('admin.filter.addValue');

    Route::delete('admin/filter/{id}/removeValue/{valueId}', [AdminFilterController::class, 'removeValue'])->name('admin.filter.removeValue');

    Route::get('admin/products', [AdminProductController::class, 'index'])->name('admin.products.index');


    Route::get('admin/users', [AdminController::class, 'users'])->name('admin.users');
});


