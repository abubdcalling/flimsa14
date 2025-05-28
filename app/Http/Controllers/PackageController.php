<?php

namespace App\Http\Controllers;

use App\Models\PackageBronze;
use App\Models\PackageGold;
use App\Models\PackageSilver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function BronzeShow()
    {
        try {
            $package = PackageBronze::first();

            if ($package) {
                $package->image = $package->image ? url('uploads/PackageBronze/' . $package->image) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Package Bronze retrieved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching package bronze: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package bronze.'
            ], 500);
        }
    }

    public function storeOrUpdateBronze(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.'
            ], 401);
        }
        try {
            $validated = $request->validate([
                'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
                'title'        => 'nullable|string|max:255',
                'no_of_client' => 'nullable|string|max:255',
                'price'        => 'nullable|string|max:255',
                'vat_type'     => 'nullable|string|max:255',
            ]);

            $package = PackageBronze::first();
            $image = $package->image ?? null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image = time() . '_package_bronze.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/PackageBronze'), $image);
            }

            $data = [
                'title'        => $validated['title'] ?? null,
                'no_of_client' => $validated['no_of_client'] ?? null,
                'price'        => $validated['price'] ?? null,
                'vat_type'     => $validated['vat_type'] ?? null,
                'image'        => $image,
            ];

            if ($package) {
                $package->update($data);
            } else {
                $package = PackageBronze::create($data);
            }

            $package->image = $package->image ? url('uploads/PackageBronze/' . $package->image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Package Bronze saved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error saving package bronze: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save package bronze.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function silverShow()
    {
        try {
            $package = PackageSilver::first();

            if ($package) {
                $package->image = $package->image ? url('uploads/PackageSilver/' . $package->image) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Package Silver retrieved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching package silver: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package silver.'
            ], 500);
        }
    }

    public function storeOrUpdateSilver(Request $request)
    {
        try {
            $validated = $request->validate([
                'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
                'title'        => 'nullable|string|max:255',
                'no_of_client' => 'nullable|string|max:255',
                'price'        => 'nullable|string|max:255',
                'vat_type'     => 'nullable|string|max:255',
            ]);

            $package = PackageSilver::first();
            $image = $package->image ?? null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image = time() . '_package_silver.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/PackageSilver'), $image);
            }

            $data = [
                'title'        => $validated['title'] ?? null,
                'no_of_client' => $validated['no_of_client'] ?? null,
                'price'        => $validated['price'] ?? null,
                'vat_type'     => $validated['vat_type'] ?? null,
                'image'        => $image,
            ];

            if ($package) {
                $package->update($data);
            } else {
                $package = PackageSilver::create($data);
            }

            $package->image = $package->image ? url('uploads/PackageSilver/' . $package->image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Package Silver saved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error saving package silver: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save package silver.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function goldShow()
    {
        try {
            $package = PackageGold::first();

            if ($package) {
                $package->image = $package->image ? url('uploads/PackageGold/' . $package->image) : null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Package Gold retrieved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching package gold: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package gold.'
            ], 500);
        }
    }

    public function storeOrUpdateGold(Request $request)
    {
        try {
            $validated = $request->validate([
                'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
                'title'        => 'nullable|string|max:255',
                'no_of_client' => 'nullable|string|max:255',
                'price'        => 'nullable|string|max:255',
                'vat_type'     => 'nullable|string|max:255',
            ]);

            $package = PackageGold::first();
            $image = $package->image ?? null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image = time() . '_package_gold.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/PackageGold'), $image);
            }

            $data = [
                'title'        => $validated['title'] ?? null,
                'no_of_client' => $validated['no_of_client'] ?? null,
                'price'        => $validated['price'] ?? null,
                'vat_type'     => $validated['vat_type'] ?? null,
                'image'        => $image,
            ];

            if ($package) {
                $package->update($data);
            } else {
                $package = PackageGold::create($data);
            }

            $package->image = $package->image ? url('uploads/PackageGold/' . $package->image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Package Gold saved successfully.',
                'data'    => $package
            ]);
        } catch (Exception $e) {
            Log::error('Error saving package gold: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save package gold.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
