<?php

namespace App\Http\Controllers\WebCms;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebCms\StoreMemberRequest;
use App\Http\Requests\WebCms\UpdateMemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * List the members.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        return view('webcms.members.index', [
            'members' => Member::search($search)
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a member.
     */
    public function create(): View
    {
        return view('webcms.members.create', [
            'member' => new Member(['is_active' => true]),
        ]);
    }

    /**
     * Store a newly created member.
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $member = Member::create($request->validated());

        return redirect()
            ->route('webcms.members.index')
            ->with('status', "Member \"{$member->name}\" has been created.");
    }

    /**
     * Show the form for editing a member.
     */
    public function edit(Member $member): View
    {
        return view('webcms.members.edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the given member.
     */
    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $member->update($data);

        return redirect()
            ->route('webcms.members.index')
            ->with('status', "Member \"{$member->name}\" has been updated.");
    }

    /**
     * Give a member their full monthly blueprint allowance back.
     */
    public function refreshQuota(Member $member): RedirectResponse
    {
        $member->refreshBlueprintQuota();

        $quota = $member->blueprintQuota();

        return back()->with(
            'status',
            "{$member->name} can generate {$quota['limit']} more Brand Blueprints this month.",
        );
    }

    /**
     * Delete the given member.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()
            ->route('webcms.members.index')
            ->with('status', "Member \"{$member->name}\" has been deleted.");
    }
}
