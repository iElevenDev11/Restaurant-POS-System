<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\TableController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\PaymentController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Default route - redirect to login
Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('staff', StaffController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('/reports/items', [ReportController::class, 'items'])->name('admin.reports.items');
    Route::get('/reports/staff', [ReportController::class, 'staff'])->name('admin.reports.staff');
});

// Cashier routes
Route::prefix('cashier')->middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');
    Route::resource('tables', TableController::class);
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('payments', PaymentController::class);
    Route::get('/kitchen-display', [CashierDashboardController::class, 'kitchenDisplay'])->name('cashier.kitchen');
});

require __DIR__ . '/auth.php';
