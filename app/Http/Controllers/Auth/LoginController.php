<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // レート制限チェック（5分間に5回まで）
        $key = Str::lower($request->input('name')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'name' => "ログイン試行回数が多すぎます。{$seconds}秒後に再試行してください。",
            ]);
        }

        // Remember Me機能を有効化（デフォルトでtrue）
        $remember = $request->input('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // セッションの有効期限を明示的に設定
            $request->session()->put('auth.login_time', now());

            RateLimiter::clear($key);
            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($key, 300); // 5分間

        return back()->withErrors([
            'name' => '名前またはパスワードが正しくありません。',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
