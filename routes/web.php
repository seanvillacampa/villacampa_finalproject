<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitOfMeasureController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierItemController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalHealthLogController;
use App\Http\Controllers\AnimalFeedLogController;
use Illuminate\Support\Facades\Route;

// ── WELCOME ───────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── DASHBOARD ─────────────────────────────────────────────────
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── AUTHENTICATED ─────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── CATEGORIES ────────────────────────────────────────────
    Route::get('/categories',            [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',     [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',           [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit',  [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',    [CategoryController::class, 'destroy'])->name('categories.destroy');

    // ── UNITS OF MEASURE ──────────────────────────────────────
    Route::get('/units',            [UnitOfMeasureController::class, 'index'])->name('units.index');
    Route::get('/units/create',     [UnitOfMeasureController::class, 'create'])->name('units.create');
    Route::post('/units',           [UnitOfMeasureController::class, 'store'])->name('units.store');
    Route::get('/units/{unit}',     [UnitOfMeasureController::class, 'show'])->name('units.show');
    Route::get('/units/{unit}/edit',       [UnitOfMeasureController::class, 'edit'])->name('units.edit');
    Route::put('/units/{unit}',     [UnitOfMeasureController::class, 'update'])->name('units.update');
    Route::delete('/units/{unit}',  [UnitOfMeasureController::class, 'destroy'])->name('units.destroy');

    // ── ITEMS ─────────────────────────────────────────────────
    Route::get('/items',            [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create',     [ItemController::class, 'create'])->name('items.create');
    Route::post('/items',           [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}',     [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit',       [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}',     [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}',  [ItemController::class, 'destroy'])->name('items.destroy');

 Route::resource('stock-outs', StockOutController::class)
     ->only(['index', 'store', 'show', 'destroy']);

    // ── SUPPLIERS ─────────────────────────────────────────────
    Route::get('/suppliers',               [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create',        [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers',              [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}',    [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit',      [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}',    [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Supplier Items
    Route::get('/suppliers/{supplier}/items',                    [SupplierItemController::class, 'index'])->name('supplier-items.index');
    Route::post('/suppliers/{supplier}/items',                   [SupplierItemController::class, 'store'])->name('supplier-items.store');
    Route::patch('/suppliers/{supplier}/items/{supplierItem}',   [SupplierItemController::class, 'update'])->name('supplier-items.update');
    Route::delete('/suppliers/{supplier}/items/{supplierItem}',  [SupplierItemController::class, 'destroy'])->name('supplier-items.destroy');

    // ── PURCHASES ─────────────────────────────────────────────
    Route::get('/purchases',               [PurchaseHistoryController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create',        [PurchaseHistoryController::class, 'create'])->name('purchases.create');
    Route::post('/purchases',              [PurchaseHistoryController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}',    [PurchaseHistoryController::class, 'show'])->name('purchases.show');
    Route::patch('/purchases/{purchase}/received', [PurchaseHistoryController::class, 'markReceived'])->name('purchases.received');
    Route::patch('/purchases/{purchase}/cancel',   [PurchaseHistoryController::class, 'cancel'])->name('purchases.cancel');

    // ── BREEDS ────────────────────────────────────────────────
    Route::get('/breeds',            [BreedController::class, 'index'])->name('breeds.index');
    Route::get('/breeds/create',     [BreedController::class, 'create'])->name('breeds.create');
    Route::post('/breeds',           [BreedController::class, 'store'])->name('breeds.store');
    Route::get('/breeds/{breed}',    [BreedController::class, 'show'])->name('breeds.show');
    Route::get('/breeds/{breed}/edit',     [BreedController::class, 'edit'])->name('breeds.edit');
    Route::put('/breeds/{breed}',    [BreedController::class, 'update'])->name('breeds.update');
    Route::delete('/breeds/{breed}', [BreedController::class, 'destroy'])->name('breeds.destroy');

    // ── ANIMALS ───────────────────────────────────────────────
    Route::get('/animals',             [AnimalController::class, 'index'])->name('animals.index');
    Route::get('/animals/create',      [AnimalController::class, 'create'])->name('animals.create');
    Route::post('/animals',            [AnimalController::class, 'store'])->name('animals.store');
    Route::get('/animals/{animal}',    [AnimalController::class, 'show'])->name('animals.show');
    Route::get('/animals/{animal}/edit',      [AnimalController::class, 'edit'])->name('animals.edit');
    Route::put('/animals/{animal}',    [AnimalController::class, 'update'])->name('animals.update');
    Route::delete('/animals/{animal}', [AnimalController::class, 'destroy'])->name('animals.destroy');

    // ── HEALTH LOGS ───────────────────────────────────────────
    Route::get('/health-logs',                [AnimalHealthLogController::class, 'index'])->name('health-logs.index');
    Route::get('/health-logs/create',         [AnimalHealthLogController::class, 'create'])->name('health-logs.create');
    Route::post('/health-logs',               [AnimalHealthLogController::class, 'store'])->name('health-logs.store');
    Route::get('/health-logs/{healthLog}',    [AnimalHealthLogController::class, 'show'])->name('health-logs.show');
    Route::get('/health-logs/{healthLog}/edit',      [AnimalHealthLogController::class, 'edit'])->name('health-logs.edit');
    Route::put('/health-logs/{healthLog}',    [AnimalHealthLogController::class, 'update'])->name('health-logs.update');
    Route::delete('/health-logs/{healthLog}', [AnimalHealthLogController::class, 'destroy'])->name('health-logs.destroy');

    // ── FEED LOGS ─────────────────────────────────────────────
    Route::get('/feed-logs',               [AnimalFeedLogController::class, 'index'])->name('feed-logs.index');
    Route::get('/feed-logs/create',        [AnimalFeedLogController::class, 'create'])->name('feed-logs.create');
    Route::post('/feed-logs',              [AnimalFeedLogController::class, 'store'])->name('feed-logs.store');
    Route::get('/feed-logs/{feedLog}',     [AnimalFeedLogController::class, 'show'])->name('feed-logs.show');
    Route::delete('/feed-logs/{feedLog}',  [AnimalFeedLogController::class, 'destroy'])->name('feed-logs.destroy');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin',                              [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/users',                        [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users',                       [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit',            [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}',                 [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::put('/admin/users/{user}/role',            [AdminUserController::class, 'assignRole'])->name('admin.users.role');
    Route::delete('/admin/users/{user}',              [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';