<?php

use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\PageSectionController;
use App\Http\Controllers\Api\PortfolioCategoryController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteSettingsController;
use App\Http\Controllers\Api\NewsletterSubscriberController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index']);
    Route::get('/portfolio/{slug}', [PortfolioController::class, 'show']);
    Route::get('/portfolio-categories', [PortfolioCategoryController::class, 'index']);

    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{slug}', [ServiceController::class, 'show']);

    // Offices
    Route::get('/offices', [OfficeController::class, 'index']);

    // Clients
    Route::get('/clients', [ClientController::class, 'index']);

    // Products
    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);

    // Awards
    Route::get('/awards', [AwardController::class, 'index']);

    // Page sections (CMS content)
    Route::get('/pages/{page}', [PageSectionController::class, 'show']);
    Route::get('/pages/{page}/{section}', [PageSectionController::class, 'section']);

    // Site-wide settings
    Route::get('/settings', [SiteSettingsController::class, 'index']);

    // Careers
    Route::get('/jobs', [\App\Http\Controllers\Api\JobController::class, 'index']);
    Route::post('/jobs/{id}/apply', [\App\Http\Controllers\Api\JobController::class, 'apply']);

    // Contact form — rate limited
    Route::middleware('throttle:5,1')->post('/contact', [ContactController::class, 'store']);

    // Newsletter subscription
    Route::middleware('throttle:10,1')->post('/newsletter/subscribe', [NewsletterSubscriberController::class, 'subscribe']);
});
