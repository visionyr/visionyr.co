<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandBlueprintRequest;
use App\Models\BrandBlueprint;
use App\Services\Blueprint\BlueprintGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandBlueprintController extends Controller
{
    /**
     * Show the discovery form, or explain that this month's allowance is spent.
     */
    public function create(Request $request): View
    {
        return view('site.create', [
            'quota' => $request->user()->blueprintQuota(),
        ]);
    }

    /**
     * Generate a blueprint from the founder's answers and store it.
     *
     * The form posts here over fetch so the generating stage can stay on screen,
     * which is why a JSON caller gets the result URL back instead of a redirect.
     */
    public function store(
        StoreBrandBlueprintRequest $request,
        BlueprintGenerator $generator,
    ): RedirectResponse|JsonResponse {
        $member = $request->user();

        if (! $member->hasBlueprintQuota()) {
            return $this->outOfQuota($request, $member->blueprintQuota());
        }

        $answers = $request->validated();

        $blueprint = BrandBlueprint::create([
            ...$answers,
            'member_id' => $member->id,
            ...$generator->generate($answers)->attributes(),
        ]);

        $member->consumeBlueprintQuota();

        $url = route('blueprint.show', $blueprint);

        return $request->expectsJson()
            ? response()->json(['url' => $url], 201)
            : redirect()->to($url);
    }

    /**
     * Show a generated blueprint.
     */
    public function show(BrandBlueprint $blueprint): View
    {
        return view('site.blueprint', [
            'blueprint' => $blueprint,
            'bp' => $blueprint->payload,
        ]);
    }

    /**
     * @param  array{limit: int, used: int, remaining: int, resets_on: \Illuminate\Support\Carbon}  $quota
     */
    protected function outOfQuota(Request $request, array $quota): RedirectResponse|JsonResponse
    {
        $message = "You have used all {$quota['limit']} Brand Blueprints for this month. "
            ."Your allowance renews on {$quota['resets_on']->format('j F')}.";

        return $request->expectsJson()
            ? response()->json(['message' => $message], 429)
            : redirect()->route('blueprint.create')->with('error', $message);
    }
}
