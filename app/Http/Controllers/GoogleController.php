<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleMeetService;

class GoogleController extends Controller
{
    public function connect(GoogleMeetService $googleMeetService)
    {
        return redirect($googleMeetService->getAuthUrl());
    }

    public function callback(Request $request, GoogleMeetService $googleMeetService)
    {
        $googleMeetService->fetchAccessToken($request->code);

        return response()->json([
            'status' => 1,
            'message' => 'Google account connected successfully'
        ]);
    }
}
