<?php

namespace App\Http\Controllers\WebCms\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebCms\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the CMS login screen.
     */
    public function create(): View
    {
        return view('webcms.auth.login');
    }

    /**
     * Sign the admin into the CMS.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('webcms.dashboard'));
    }

    /**
     * Sign the admin out of the CMS.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('webcms.login');
    }
}
