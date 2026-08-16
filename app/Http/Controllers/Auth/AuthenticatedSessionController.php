<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        // Получаем аутентифицированного пользователя
        $user = Auth::user();
        if (!$user->roles()->exists()) {
            // Разлогиниваем, чтобы сессия не считалась валидной
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Нет права доступа.'); //TODO файл перевода
        }

        $request->session()->regenerate();

        // Проверка: не подтверждён email И домен временный
        if ($user->email === null || str_ends_with( $user->email,'@admincreate.com'))
        {
            // Добавляем сообщение в сессию (оно пропадёт после показа)
            session()->flash('profile_warning', [
                'type' => 'warning',
                'message' => 'Please provide your real email address.', //TODO файл перевода
            ]);

            return redirect()->route('profile.update');
        }

        if ( $user->requires_password_change === 1)
        {
            // Добавляем сообщение в сессию (оно пропадёт после показа)
            session()->flash('profile_warning', [
                'type' => 'warning',
                'message' => 'Please set a permanent password.', //TODO файл перевода
            ]);

            return redirect()->route('password.update');
        }


        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
