<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionPersists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // セッションを開始して保存を確実にする
        if ($request->hasSession()) {
            $request->session()->save();
        }

        $response = $next($request);

        // レスポンス後もセッションを保存
        if ($request->hasSession()) {
            $request->session()->save();
        }

        return $response;
    }
}
