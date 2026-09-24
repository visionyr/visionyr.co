<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    /**
     * Show a legal document defined in config/legal.php.
     */
    public function __invoke(string $document): View
    {
        return view('site.legal', [
            'document' => config("legal.{$document}"),
        ]);
    }
}
