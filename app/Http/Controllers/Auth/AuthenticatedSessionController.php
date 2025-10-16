<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Tangani proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user
        $request->authenticate();

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // ✅ Setelah login, arahkan ke HOME (sekarang /items)
        // dan kirim status 'login-success'
        return redirect()->intended(RouteServiceProvider::HOME)
            ->with('status', 'login-success');
    }

    /**
     * Logout user dari aplikasi.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
