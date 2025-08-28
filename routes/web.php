<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\MachineryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UsageRecordController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ✅ AUTHENTICATED ROUTES
Route::middleware(['auth', 'verified'])->group(function () {

    // 🔄 Redirect after login based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'staff') {
            return redirect()->route('fieldsupervisor.dashboard');
        } else {
            return redirect()->route('public.dashboard');
        }
    })->name('dashboard');

    // ✅ DASHBOARDS
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/fieldsupervisor/dashboard', [DashboardController::class, 'supervisorDashboard'])->name('fieldsupervisor.dashboard');

    // ✅ COMMODITIES
    Route::resource('commodities', CommodityController::class)->except(['show']);
    Route::get('/commodities/fsindex', [CommodityController::class, 'fsindex'])->name('commodities.fsindex');
    
    // ✅ ORDER STOCK ROUTES
    Route::get('/commodities/order-stock', [CommodityController::class, 'orderStock'])->name('commodities.orderstock');
    Route::post('/commodities/generate-order-pdf', [CommodityController::class, 'generateOrderPdf'])->name('commodities.generate-order-pdf');

    // ✅ MACHINERY
    Route::resource('machinery', MachineryController::class)->except(['show', 'create']);
    Route::get('/machinery/fsindex', [MachineryController::class, 'fsindex'])->name('machinery.fsindex');
    Route::get('/machinery/{machinery}/details', [MachineryController::class, 'details'])->name('machinery.details');

    // ✅ EQUIPMENT
    Route::resource('equipment', EquipmentController::class)->except(['show']);
    Route::get('/equipment/fsindex', [EquipmentController::class, 'fsindex'])->name('equipment.fsindex');

    // ✅ USAGE RECORDS
    Route::resource('usagerecords', UsageRecordController::class)->except(['show']);
    Route::get('/usagerecords/fsindex', [UsageRecordController::class, 'fsindex'])->name('usagerecords.fsindex');

    // ✅ ORDERS
    Route::resource('orders', OrderController::class)->only(['store', 'index']);
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // ✅ PROFILE MANAGEMENT
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ ADMIN - USER MANAGEMENT
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/users/dashboard', function () {
            return view('admin.users.dashboard');
        })->name('users.dashboard');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';