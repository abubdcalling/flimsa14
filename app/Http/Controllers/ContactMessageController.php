<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    // Show the first contact message
    public function show()
    {
        try {
            $msg = ContactMessage::first();

            if ($msg) {
                return response()->json([
                    'success' => true,
                    'message' => 'Contact message fetched successfully.',
                    'data' => $msg
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No contact message found.'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Error fetching contact message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contact message.'
            ], 500);
        }
    }

    // Store a new contact message
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'   => 'required|string|max:255',
                'last_name'    => 'required|string|max:255',
                // 'email'        => 'required|email|phone:*',
                'email'        => 'required|email',
                'phone'        => 'required|string|max:20',
                'organization' => 'nullable|string|max:255',
                'city'         => 'nullable|string|max:255',
                'help'         => 'nullable|string',
            ]);

            $contact = ContactMessage::create($validated);

            // Send email to admin
            Mail::to('abubdcalling@gmail.com')->send(new ContactMessageMail($contact));

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.',
                'last_inserted_id' => $contact->id,
                'data' => $contact
            ], 201);
        } catch (Exception $e) {
            Log::error('Error saving contact message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save contact message.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
