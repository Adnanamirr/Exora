<?php

use App\Http\Controllers\backend\admin\AdminController;
use App\Http\Controllers\backend\admin\PlansController;
use App\Http\Controllers\backend\admin\TemplateController;
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
    Route::get('/add-plan',  'AddPlan')->name('add.plan');
    Route::post('/add-plan',  'StorePlan')->name('add.plan.store');
    Route::get('/edit-plan/{id}',  'EditPlan')->name('edit.plan');
    Route::put('/update-plan/{id}', 'UpdatePlan')->name('update.plan');
    Route::delete('/delete-plan/{id}', 'Destroy')->name('delete.plan');
});

Route::controller(TemplateController::class)->group(function () {
   Route::get('/all-templates', 'AllTemplates')->name('all.templates');
    Route::get('/add-template',  'AddTemplate')->name('add.template');
    Route::post('/add-template',  'StoreTemplate')->name('store.template');
    Route::get('/edit-template/{id}',  'EditTemplate')->name('edit.template');
    Route::put('/update-template/{id}', 'UpdateTemplate')->name('update.template');
    Route::get('/template-details/{id}', 'TemplateDetails')->name('details.template');
    Route::delete('/delete-template/{id}', 'DestroyTemplate')->name('delete.template');


});


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
