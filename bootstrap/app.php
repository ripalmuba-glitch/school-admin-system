<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // PENTING: Import RoleMiddleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // --- DAFTARKAN MIDDLEWARE ALIAS KUSTOM DI SINI ---
        $middleware->alias([
            'role' => RoleMiddleware::class, // <-- Baris ini mendaftarkan 'role' middleware
        ]);
        // --------------------------------------------------

        // Grup Middleware bawaan yang mungkin ada (tergantung starter kit Breeze)
        // $middleware->web(append: [
        //     \App\Http\Middleware\HandleInertiaRequests::class,
        //     \Illuminate\Http\Middleware\AddLinkHeaders::class,
        // ]);

        // Menonaktifkan middleware tertentu (Opsional, tergantung kebutuhan)
        // $middleware->remove(\Illuminate\Cookie\Middleware\EncryptCookies::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Penanganan pengecualian kustom dapat ditambahkan di sini
    })
    ->create();
