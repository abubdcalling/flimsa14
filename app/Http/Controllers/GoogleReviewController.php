<?php

namespace App\Http\Controllers;

use App\Services\GoogleService;
use Illuminate\Support\Facades\Http;

class GoogleReviewController extends Controller
{
    public function getGoogleReviews()
    {
        $apiKey = env('GOOGLE_REVIEW_API_KEY');
        $placeId = env('SHOP_PLACE_ID');
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&fields=name,rating,reviews&key=$apiKey";

        $response = Http::get($url);
        return $response->json();
    }
}
