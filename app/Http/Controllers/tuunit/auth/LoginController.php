<?php

namespace App\Http\Controllers\tuunit\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TuUnitRequest;
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
        return view('tuunit.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(TuUnitRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('tuunit.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('tuunit')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('tuunit.login'));
    }
}