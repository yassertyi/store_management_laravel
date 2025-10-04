<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserType; // ✅ استدعاء الميدل وير

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:[
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/seller.php',
            __DIR__.'/../routes/customer.php',
        ] ,
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ تسجيل اسم مستعار للميدل وير
        $middleware->alias([
            'checkUserType' => CheckUserType::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
