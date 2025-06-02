<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Laravel expects 'password_confirmation' field
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => '', // default or allow in request if needed
            'email' => $request->email,
            'username' => explode('@', $request->email)[0], // simple username generator
            'password' => Hash::make($request->password),
            'roles' => 'subscriber',
            'country' => '',
            'city' => '',
            'phone' => '',
            'plan_type' => 'none',
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }

    // Login user and get token
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 400);
            }

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid credentials.'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token
            ]);
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Login failed.'
            ], 500);
        }
    }

    // Get authenticated user
    public function me()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'User details fetched successfully.',
                'data' => auth()->user()
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user details.'
            ], 500);
        }
    }

    // Logout user (invalidate token)
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout.'
            ], 500);
        }
    }



    // public function sendResetOTP(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return response()->json(['success' => false, 'message' => 'User not found.'], 404);
    //     }

    //     $otp = rand(100000, 999999);

    //     $user->reset_otp = $otp;
    //     $user->otp_expires_at = Carbon::now()->addMinutes(10);
    //     $user->save();

    //     Mail::raw("Your password reset OTP is: $otp", function ($message) use ($user) {
    //         $message->to($user->email)
    //             ->subject('Password Reset OTP');
    //     });

    //     return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    // }


    // public function verifyResetOTP(Request $request)
    // {
    //     $request->validate([
    //         'otp' => 'required|digits:6',
    //     ]);

    //     $user = User::where('reset_otp', $request->otp)->first();

    //     if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
    //         return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
    //     }

    //     $user->otp_verified_at = Carbon::now();
    //     $user->save();

    //     session(['password_reset_user_id' => $user->id]);

    //     return response()->json(['success' => true, 'message' => 'OTP verified. You may now reset your password.']);
    // }


    // public function passwordReset(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $userId = session('password_reset_user_id');

    //     if (!$userId || !$user = User::find($userId)) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized or session expired.'], 403);
    //     }

    //     $user->password = bcrypt($request->password);
    //     $user->reset_otp = null;
    //     $user->otp_expires_at = null;
    //     $user->otp_verified_at = null;
    //     $user->save();

    //     session()->forget('password_reset_user_id');

    //     return response()->json(['success' => true, 'message' => 'Password reset successful.']);
    // }

    public function sendResetOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $otp = rand(100000, 999999);

        $user->reset_otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::raw("Your password reset OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    public function verifyResetOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_otp', $request->otp)
            ->first();

        if (!$user || Carbon::now()->gt($user->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        $user->otp_verified_at = Carbon::now();
        $user->save();

        return response()->json(['success' => true, 'message' => 'OTP verified. You may now reset your password.']);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->whereNotNull('otp_verified_at')
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired reset link or verification.'], 403);
        }

        $user->password = bcrypt($request->password);
        $user->reset_otp = null;
        $user->otp_expires_at = null;
        $user->otp_verified_at = null;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password reset successful.']);
    }






    public function sendEmail()
    {
        $user = User::find(1); // fetch user with ID 1

        if ($user) {
            return response()->json([
                'email' => $user->email,
            ]);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }
}
