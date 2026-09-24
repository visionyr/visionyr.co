<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Member;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration screen.
     */
    public function create(): View
    {
        return view('site.auth.register');
    }

    /**
     * Register a new member and sign them in.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // last_login_at stays null: registering is not a sign-in, and the
        // dashboard says so until they come back.
        $member = Member::create([
            ...$request->validated(),
            'is_active' => true,
        ]);

        event(new Registered($member));

        Auth::login($member);

        $request->session()->regenerate();

        // Someone sent here from /create should land back on it, not the dashboard.
        return redirect()->intended(route('dashboard'));
    }
}
