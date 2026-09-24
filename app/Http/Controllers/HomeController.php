<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the marketing home page.
     */
    public function __invoke(): View
    {
        return view('site.home');
    }
}
