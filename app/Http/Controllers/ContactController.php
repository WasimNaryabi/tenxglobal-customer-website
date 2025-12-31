<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    /**
     * Display the contact us page
     */
    public function index(): Response
    {
        return Inertia::render('Contact');
    }

    /**
     * Handle contact form submission
     */
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Save to database or send email
        // ContactMessage::create($validated);
        
        // Or send email
        // Mail::to('admin@foodhub.com')->send(new ContactFormMail($validated));

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}