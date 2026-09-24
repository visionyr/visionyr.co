<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the signed-in member their profile and blueprints.
     */
    public function __invoke(Request $request): View
    {
        $member = $request->user();

        return view('site.dashboard', [
            'member' => $member,
            'blueprints' => $member->brandBlueprints()->latest()->get(),
            'quota' => $member->blueprintQuota(),
        ]);
    }
}
