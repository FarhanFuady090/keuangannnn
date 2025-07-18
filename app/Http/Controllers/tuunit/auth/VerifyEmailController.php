<?php

namespace App\Http\Controllers\tuunit\auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated tuunit's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->tuunit()->hasVerifiedEmail()) {
            return redirect()->intended(route('tuunit.dashboard', absolute: false) . '?verified=1');
        }

        if ($request->tuunit()->markEmailAsVerified()) {
            event(new Verified($request->tuunit()));
        }

        return redirect()->intended(route('tuunit.dashboard', absolute: false) . '?verified=1');
    }
}
