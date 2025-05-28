<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class SeoController extends Controller
{
    public function show($select_page)
    {
        try {
            $seo = Seo::where('select_page', $select_page)->first();

            return response()->json([
                'success' => true,
                'message' => 'SEO data retrieved successfully.',
                'data'    => $seo
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching SEO data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve SEO data.'
            ], 500);
        }
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'select_page'      => 'required|string|max:255',
                'meta_title'       => 'nullable|string',
                'meta_description' => 'nullable|string',
                'meta_keywords'    => 'nullable|string',
            ]);

            $seo = Seo::where('select_page', $validated['select_page'])->first();

            $data = [
                'select_page'      => $validated['select_page'],
                'meta_title'       => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'meta_keywords'    => $validated['meta_keywords'] ?? null,
            ];

            if ($seo) {
                $seo->update($data);
            } else {
                $seo = Seo::create($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'SEO data saved successfully.',
                'data'    => $seo
            ]);
        } catch (Exception $e) {
            Log::error('Error saving SEO data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save SEO data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
