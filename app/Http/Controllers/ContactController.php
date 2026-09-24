<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Show the contact page.
     */
    public function create(): View
    {
        return view('site.contact');
    }

    /**
     * Record an enquiry.
     */
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::create($request->validated());

        return redirect()
            ->route('contact')
            ->with('contact_sent', $message->name);
    }
}
