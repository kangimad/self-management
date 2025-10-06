<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard-old', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard-old');
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Finance routes with permissions
Route::middleware(['auth', 'verified'])->prefix('finance')->name('finance.')->group(function () {

    // Transaction routes
    Route::middleware('permission:finance-transaction-list')->group(function () {
        Route::get('/transactions', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'show'])->name('transactions.show');
    });

    Route::middleware('permission:finance-transaction-create')->group(function () {
        Route::get('/transactions/create', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'store'])->name('transactions.store');
    });

    Route::middleware('permission:finance-transaction-edit')->group(function () {
        Route::get('/transactions/{transaction}/edit', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'edit'])->name('transactions.edit');
        Route::patch('/transactions/{transaction}', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'update'])->name('transactions.update');
    });

    Route::middleware('permission:finance-transaction-delete')->group(function () {
        Route::delete('/transactions/{transaction}', [\App\Http\Controllers\Finance\FinanceTransactionController::class, 'destroy'])->name('transactions.destroy');
    });

    // Category routes
    Route::middleware('permission:finance-category-list')->group(function () {
        Route::get('/categories', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'show'])->name('categories.show');
    });

    Route::middleware('permission:finance-category-create')->group(function () {
        Route::get('/categories/create', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'store'])->name('categories.store');
    });

    Route::middleware('permission:finance-category-edit')->group(function () {
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'edit'])->name('categories.edit');
        Route::patch('/categories/{category}', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'update'])->name('categories.update');
    });

    Route::middleware('permission:finance-category-delete')->group(function () {
        Route::delete('/categories/{category}', [\App\Http\Controllers\Finance\FinanceCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Source routes
    Route::middleware('permission:finance-source-list')->group(function () {
        Route::get('/sources', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'index'])->name('sources.index');
        Route::get('/sources/{source}', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'show'])->name('sources.show');
    });

    Route::middleware('permission:finance-source-create')->group(function () {
        Route::get('/sources/create', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'create'])->name('sources.create');
        Route::post('/sources', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'store'])->name('sources.store');
    });

    Route::middleware('permission:finance-source-edit')->group(function () {
        Route::get('/sources/{source}/edit', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'edit'])->name('sources.edit');
        Route::patch('/sources/{source}', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'update'])->name('sources.update');
    });

    Route::middleware('permission:finance-source-delete')->group(function () {
        Route::delete('/sources/{source}', [\App\Http\Controllers\Finance\FinanceSourceController::class, 'destroy'])->name('sources.destroy');
    });

    // Balance routes
    Route::middleware('permission:finance-balance-list')->group(function () {
        Route::get('/balances', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'index'])->name('balances.index');
        Route::get('/balances/{balance}', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'show'])->name('balances.show');
    });

    Route::middleware('permission:finance-balance-create')->group(function () {
        Route::get('/balances/create', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'create'])->name('balances.create');
        Route::post('/balances', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'store'])->name('balances.store');
    });

    Route::middleware('permission:finance-balance-edit')->group(function () {
        Route::get('/balances/{balance}/edit', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'edit'])->name('balances.edit');
        Route::patch('/balances/{balance}', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'update'])->name('balances.update');
    });

    Route::middleware('permission:finance-balance-delete')->group(function () {
        Route::delete('/balances/{balance}', [\App\Http\Controllers\Finance\FinanceBalanceController::class, 'destroy'])->name('balances.destroy');
    });

    // Allocation routes
    Route::middleware('permission:finance-allocation-list')->group(function () {
        Route::get('/allocations', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'index'])->name('allocations.index');
        Route::get('/allocations/{allocation}', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'show'])->name('allocations.show');
    });

    Route::middleware('permission:finance-allocation-create')->group(function () {
        Route::get('/allocations/create', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'create'])->name('allocations.create');
        Route::post('/allocations', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'store'])->name('allocations.store');
    });

    Route::middleware('permission:finance-allocation-edit')->group(function () {
        Route::get('/allocations/{allocation}/edit', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'edit'])->name('allocations.edit');
        Route::patch('/allocations/{allocation}', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'update'])->name('allocations.update');
    });

    Route::middleware('permission:finance-allocation-delete')->group(function () {
        Route::delete('/allocations/{allocation}', [\App\Http\Controllers\Finance\FinanceAllocationController::class, 'destroy'])->name('allocations.destroy');
    });
});

// User routes with permissions
Route::middleware(['auth', 'verified'])->prefix('setting')->name('setting.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SettingController::class, 'index'])->name('index');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/datatable', [\App\Http\Controllers\UserController::class, 'datatableData'])->name('datatable');
        Route::get('/export', [\App\Http\Controllers\UserController::class, 'export'])->name('export');
        Route::get('/roles', [\App\Http\Controllers\UserController::class, 'getRoles'])->name('roles');
        Route::post('/', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('show');
        Route::put('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::patch('/{user}/detail', [\App\Http\Controllers\UserController::class, 'updateDetail'])->name('update.detail');
        Route::patch('/{user}/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('update.password');
        Route::patch('/{user}/role', [\App\Http\Controllers\UserController::class, 'updateRole'])->name('update.role');
        Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        Route::delete('/', [\App\Http\Controllers\UserController::class, 'destroyMultiple'])->name('destroy.multiple');
    });
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RoleController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\RoleController::class, 'store'])->name('store');
        Route::get('/{role}', [\App\Http\Controllers\RoleController::class, 'show'])->name('show');
        Route::get('/{role}/users', [\App\Http\Controllers\RoleController::class, 'getUsersWithRole'])->name('users');
        Route::get('/{role}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('destroy');
        Route::delete('/', [\App\Http\Controllers\RoleController::class, 'destroyMultiple'])->name('destroy.multiple');
        Route::post('/remove-user', [\App\Http\Controllers\RoleController::class, 'removeUser'])->name('remove.user');
        Route::post('/remove-multiple-users', [\App\Http\Controllers\RoleController::class, 'removeMultipleUsers'])->name('remove.multiple.users');
    });
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PermissionController::class, 'index'])->name('index');
        Route::get('/datatable', [\App\Http\Controllers\PermissionController::class, 'datatableData'])->name('datatable');
        Route::post('/', [\App\Http\Controllers\PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}', [\App\Http\Controllers\PermissionController::class, 'show'])->name('show');
        Route::put('/{permission}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('destroy');
        Route::delete('/', [\App\Http\Controllers\PermissionController::class, 'destroyMultiple'])->name('destroy.multiple');
        Route::get('/search', [\App\Http\Controllers\PermissionController::class, 'search'])->name('search');
        Route::get('/stats', [\App\Http\Controllers\PermissionController::class, 'stats'])->name('stats');
    });
});

require __DIR__ . '/auth.php';
