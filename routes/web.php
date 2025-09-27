<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PincodeLookupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use App\Livewire\CompanySettingsForm;
use App\Livewire\CustomersManager;
use App\Livewire\Dashboard;
use App\Livewire\ExpensesManager;
use App\Livewire\ProductsManager;
use App\Livewire\PurchasesManager;
use App\Livewire\QuotesManager;
use App\Livewire\RolesManager;
use App\Livewire\SuppliersManager;
use App\Livewire\UnitsManager;
use App\Livewire\UsersManager;
use App\Livewire\WarehousesManager;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('products', ProductsManager::class)->name('products.index');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');

    Route::get('warehouses', WarehousesManager::class)->name('warehouses.index');
    Route::get('warehouses/export', [WarehouseController::class, 'export'])->name('warehouses.export');

    Route::get('units', UnitsManager::class)->name('units.index');
    Route::get('suppliers', SuppliersManager::class)->name('suppliers.index');
    Route::get('customers', CustomersManager::class)->name('customers.index');
    Route::get('quotes', QuotesManager::class)->name('quotes.index');
    Route::get('purchases', PurchasesManager::class)->name('purchases.index');
    Route::get('expenses', ExpensesManager::class)->name('expenses.index');
    Route::get('units/export', [UnitController::class, 'export'])->name('units.export');

    Route::get('company-settings', CompanySettingsForm::class)->name('company-settings.edit');

    Route::prefix('admin')->group(function () {
        Route::get('accounts', UsersManager::class)->name('admin.users');
        Route::get('roles', RolesManager::class)->name('admin.roles');
    });

    Route::get('api/pincode-lookup', PincodeLookupController::class)->name('api.pincode-lookup');

    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

});
