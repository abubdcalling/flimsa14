<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $profile = Profile::first();

            if ($profile) {
                $profile->image = $profile->image ? url('uploads/Profiles/' . $profile->image) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully.',
                'data'    => $profile
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching profile: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile.'
            ], 500);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
                'first_name'   => 'nullable|string|max:255',
                'last_name'    => 'nullable|string|max:255',
                'email'        => 'required|email|max:255',
                'position'     => 'nullable|string|max:255',
                'website_link' => 'nullable|string',
                'about'        => 'nullable|string',
                'phone'        => 'nullable|string|max:20',
                'address'      => 'nullable|string',
                'city'         => 'nullable|string|max:100',
                'state'        => 'nullable|string|max:100',
                'zip_code'     => 'nullable|string|max:20',
                'country'      => 'nullable|string|max:100',
            ]);

            $profile = Profile::first();
            $image = $profile->image ?? null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image = time() . '_profile.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/Profiles'), $image);
            }

            $data = [
                'first_name'   => $validated['first_name'] ?? null,
                'last_name'    => $validated['last_name'] ?? null,
                'email'        => $validated['email'],
                'position'     => $validated['position'] ?? null,
                'website_link' => $validated['website_link'] ?? null,
                'about'        => $validated['about'] ?? null,
                'phone'        => $validated['phone'] ?? null,
                'address'      => $validated['address'] ?? null,
                'city'         => $validated['city'] ?? null,
                'state'        => $validated['state'] ?? null,
                'zip_code'     => $validated['zip_code'] ?? null,
                'country'      => $validated['country'] ?? null,
                'image'        => $image,
            ];

            if ($profile) {
                $profile->update($data);
            } else {
                $profile = Profile::create($data);
            }

            $profile->image = $profile->image ? url('uploads/Profiles/' . $profile->image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Profile saved successfully.',
                'data'    => $profile
            ]);
        } catch (Exception $e) {
            Log::error('Error saving profile: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save profile.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
