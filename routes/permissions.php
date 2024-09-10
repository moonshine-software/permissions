<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Http\Middleware\Authenticate;
use MoonShine\Permissions\Http\Controllers\PermissionController;

Route::moonshine(static function (Router $r) {
    $r->post(
        'permissions/{resourceItem}',
        PermissionController::class
    )->middleware(Authenticate::class)->name('permissions');
}, true);
