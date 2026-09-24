<?php

namespace App\Http\Controllers\WebCms;

use App\Http\Controllers\Controller;
use App\Models\BrandBlueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandBlueprintController extends Controller
{
    /**
     * List the generated blueprints.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        return view('webcms.brand-blueprints.index', [
            'blueprints' => BrandBlueprint::with('member')
                ->search($search)
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
            'stats' => [
                'total' => BrandBlueprint::count(),
                'ai' => BrandBlueprint::where('generator', 'openrouter')->count(),
                'fallback' => BrandBlueprint::where('generator', 'template')->count(),
            ],
        ]);
    }

    /**
     * Show one blueprint: the answers that produced it, and the result.
     */
    public function show(BrandBlueprint $blueprint): View
    {
        return view('webcms.brand-blueprints.show', [
            'blueprint' => $blueprint->load('member'),
            'bp' => $blueprint->payload,
        ]);
    }

    /**
     * Delete the given blueprint.
     */
    public function destroy(BrandBlueprint $blueprint): RedirectResponse
    {
        $blueprint->delete();

        return redirect()
            ->route('webcms.brand-blueprints.index')
            ->with('status', "Blueprint for \"{$blueprint->brand_name}\" has been deleted.");
    }
}
