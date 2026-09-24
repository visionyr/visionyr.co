<?php

namespace App\Http\Controllers\WebCms;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebCms\StoreAdminUserRequest;
use App\Http\Requests\WebCms\UpdateAdminUserRequest;
use App\Models\AdminUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * List the admin users.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        return view('webcms.admin-users.index', [
            'adminUsers' => AdminUser::search($search)
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating an admin user.
     */
    public function create(): View
    {
        return view('webcms.admin-users.create', [
            'adminUser' => new AdminUser(['is_active' => true]),
        ]);
    }

    /**
     * Store a newly created admin user.
     */
    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        $adminUser = AdminUser::create($request->validated());

        return redirect()
            ->route('webcms.admin-users.index')
            ->with('status', "Admin user \"{$adminUser->name}\" has been created.");
    }

    /**
     * Show the form for editing an admin user.
     */
    public function edit(AdminUser $adminUser): View
    {
        return view('webcms.admin-users.edit', [
            'adminUser' => $adminUser,
        ]);
    }

    /**
     * Update the given admin user.
     */
    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        // An admin must not be able to lock themselves out of the CMS.
        if ($adminUser->is($request->user('admin'))) {
            $data['is_active'] = true;
        }

        $adminUser->update($data);

        return redirect()
            ->route('webcms.admin-users.index')
            ->with('status', "Admin user \"{$adminUser->name}\" has been updated.");
    }

    /**
     * Delete the given admin user.
     */
    public function destroy(Request $request, AdminUser $adminUser): RedirectResponse
    {
        // Blocking self-deletion also guarantees at least one admin always remains.
        if ($adminUser->is($request->user('admin'))) {
            return back()->with('error', 'You cannot delete the account you are signed in with.');
        }

        $adminUser->delete();

        return redirect()
            ->route('webcms.admin-users.index')
            ->with('status', "Admin user \"{$adminUser->name}\" has been deleted.");
    }
}
