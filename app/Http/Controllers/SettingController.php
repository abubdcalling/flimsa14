<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function show()
    {
        try {
            $setting = Setting::first();

            if ($setting) {
                $setting->icon = $setting->icon ? url('uploads/Settings/' . $setting->icon) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings retrieved successfully.',
                'data'    => $setting
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve settings.'
            ], 500);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'icon'          => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
                'system_name'   => 'nullable|string|max:255',
                'system_title'  => 'nullable|string',
                'system_address' => 'nullable|string',
                'email'         => 'nullable|email|max:255',
                'phone'         => 'nullable|string|max:20',
                'opening_hour'  => 'nullable|string|max:100',
                'description'   => 'nullable|string',
            ]);

            $setting = Setting::first();
            $icon = $setting->icon ?? null;

            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $icon = time() . '_setting_icon.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/Settings'), $icon);
            }

            $data = [
                'system_name'   => $validated['system_name'] ?? null,
                'system_title'  => $validated['system_title'] ?? null,
                'system_address' => $validated['system_address'] ?? null,
                'email'         => $validated['email'] ?? null,
                'phone'         => $validated['phone'] ?? null,
                'opening_hour'  => $validated['opening_hour'] ?? null,
                'description'   => $validated['description'] ?? null,
                'icon'          => $icon,
            ];

            if ($setting) {
                $setting->update($data);
            } else {
                $setting = Setting::create($data);
            }

            $setting->icon = $setting->icon ? url('uploads/Settings/' . $setting->icon) : null;

            return response()->json([
                'success' => true,
                'message' => 'Settings saved successfully.',
                'data'    => $setting
            ]);
        } catch (Exception $e) {
            Log::error('Error saving settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }





    public function updateEmail(Request $request)
    {
        try {
            // Validate new email (must be unique except current user's email)
            $validated = $request->validate([
                'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            ]);

            // Get authenticated user
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated.'
                ], 401);
            }

            // Update email
            $user->email = $validated['email'];
            if (method_exists($user, 'save')) {
                $user->save();
            } else {
                // If $user is not an Eloquent model, update via query builder
                DB::table('users')->where('id', $user->id)->update(['email' => $validated['email']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Email updated successfully.',
                'data'    => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user email: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update email.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }




    public function updatePassword(Request $request)
    {
        // return 'ok'
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = Auth::user();

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password.',
            ], 500);
        }
    }
}
