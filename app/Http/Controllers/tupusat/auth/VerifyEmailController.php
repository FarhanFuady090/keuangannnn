<?php

namespace App\Http\Controllers\tupusat\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated tupusat's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->tupusat()->hasVerifiedEmail()) {
            return redirect()->intended(route('tupusat.dashboard.index', absolute: false) . '?verified=1');
        }

        if ($request->tupusat()->markEmailAsVerified()) {
            event(new Verified($request->tupusat()));
        }

        return redirect()->intended(route('tupusat.dashboard.index', absolute: false) . '?verified=1');
    }
}
