<?php

use App\Http\Controllers\backend\admin\AdminController;
use App\Http\Controllers\backend\admin\PlansController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::controller(AdminController::class)->group(function () {
        Route::get('/logout', 'AdminLogout')->name('admin.logout');
        Route::get('/profile', 'AdminProfile')->name('admin.profile');
        Route::post('/profile', 'AdminProfileUpdate')->name('admin.profile.Update');
        Route::get('/password', 'AdminChangePassword')->name('admin.change.password');
        Route::post('/password', 'AdminPasswordUpdate')->name('admin.password.update');
    });
Route::controller(PlansController::class)->group(function () {

    Route::get('/all-plans',  'AllPlans')->name('all.plans');

});

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
