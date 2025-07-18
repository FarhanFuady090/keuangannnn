<?php

namespace App\Http\Controllers\tupusat\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TuPusatRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('tupusat.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(TuPusatRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('tupusat.dashboard.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard(name: 'tupusat')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('tupusat.login'));
    }
}