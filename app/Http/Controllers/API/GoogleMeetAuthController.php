<?php

namespace App\Http\Controllers\API;

use App\Models\DoctorGoogleAccount;
use App\Services\GoogleMeetService;
use Illuminate\Http\Request;

class GoogleMeetAuthController extends BaseController
{
    public function connectUrl(Request $request, GoogleMeetService $googleMeetService)
    {
        $doctor = $request->user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can connect Google Meet.');
        }

        $url = $googleMeetService->generateConnectUrl($doctor);

        return $this->sendResponse([
            'connect_url' => $url,
        ], 'Google connect URL generated successfully.');
    }

    public function callback(Request $request, GoogleMeetService $googleMeetService)
    {
        $request->validate([
            'code' => 'required|string',
            'state' => 'required|string',
        ]);

        try {
            $account = $googleMeetService->handleCallback(
                $request->code,
                $request->state
            );

            return response()->json([
                'status' => true,
                'message' => 'Google Meet connected successfully.',
                'google_email' => $account->google_email,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function status(Request $request)
    {
        $doctor = $request->user();

        if ($doctor->role !== 'doctor') {
            return $this->sendError('Only doctors can check Google Meet status.');
        }

        $googleAccount = DoctorGoogleAccount::where('doctor_id', $doctor->id)->first();

        return $this->sendResponse([
            'is_connected' => $googleAccount ? (bool) $googleAccount->is_connected : false,
            'google_email' => $googleAccount->google_email ?? null,
        ], 'Google Meet status fetched successfully.');
    }
}
