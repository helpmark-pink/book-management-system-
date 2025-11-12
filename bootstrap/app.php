<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SecureHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 本番環境でのエラーレポート設定
        $exceptions->report(function (Throwable $e) {
            if (app()->environment('production')) {
                // ここで外部ログサービス（Sentry、Bugsnag等）にレポート可能
                try {
                    $userId = auth()->check() ? auth()->id() : null;
                } catch (\Exception $authException) {
                    $userId = null;
                }

                \Illuminate\Support\Facades\Log::error($e->getMessage(), [
                    'exception' => $e,
                    'user_id' => $userId,
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                ]);
            }
        });
    })->create();
