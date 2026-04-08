<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\KnowledgeEntryController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComplexityController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/workflows/{slug}', [WorkflowController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category_slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/complexity/{level}', [ComplexityController::class, 'show'])->name('complexity.show');
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool}', [ToolController::class, 'show'])->name('tools.show');
Route::get('/top/{dimension}/{slug}', [TopController::class, 'show'])->name('top.show');;

Route::get('/elasticsearch/errors', function () {
    return view('/static/category_index');
})->name('es-errors');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('knowledge', KnowledgeEntryController::class);
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

Route::get('/search', [SearchController::class, 'query']);

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap-workflows.xml', [SitemapController::class, 'workflows']);
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories']);
Route::get('/sitemap-tools.xml', [SitemapController::class, 'tools']);
Route::get('/sitemap-tags.xml', [SitemapController::class, 'tags']);
Route::get('/sitemap-complexity.xml', [SitemapController::class, 'complexity']);
Route::get('/sitemap-top.xml', [SitemapController::class, 'top']);


require __DIR__.'/auth.php';
