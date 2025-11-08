<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // Pastikan ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Daftarkan RoleMiddleware Anda
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // --- INI ADALAH PERBAIKAN UNTUK MASALAH CSS HANCUR ---
        // Memberi tahu Laravel untuk mempercayai semua proxy (seperti Railway)
        $middleware->trustProxies(at: '*');
        // --------------------------------------------------

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })
    ->create();
