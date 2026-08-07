<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Services\GoogleMeetService;
use Google\Client;
use Illuminate\Http\Request;

class MeetController extends BaseController
{
    protected $meetService,$client_id,$client_secret,$redirectUrl;

    public function __construct(GoogleMeetService $meetService)
    {
        $this->meetService = $meetService;
        $this->client_id = '';
        $this->client_secret = '';
        $this->redirectUrl = '';
    }

    /**
     * List all Google Meet events
     */
    public function list()
    {
        // $client = new Client();
        // $client->setClientId($this->client_id);
        // $client->setClientSecret($this->client_secret);
        // $client->setRedirectUri($this->redirectUrl);

        // $token = $client->fetchAccessTokenWithAuthCode($request->code);
        // return response()->json(['status'=>0,'message'=>"success",'data'=>['token'=>$token]]);
        $result = $this->meetService->listMeetEvents();

        return response()->json([
            'total' => $result['total'],
            'events' => array_map(function ($event) {
                return [
                    'event_id' => $event->getId(),
                    'title' => $event->getSummary(),
                    'start_time' => $event->getStart()->getDateTime(),
                    'meet_link' => $this->extractMeetLink($event)
                ];
            }, $result['events'] ?? [])
        ]);
    }

    /**
     * Create a new Google Meet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'attendees' => 'nullable|array',
            'attendees.*' => 'email'
        ]);

        $result = $this->meetService->createMeet($validated);

        if ($result['success']) {
            return response()->json([
                'message' => 'Google Meet created successfully',
                'data' => [
                    'event_id' => $result['eventId'],
                    'meet_link' => $result['meetLink']
                ]
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create Google Meet',
            'error' => $result['error']
        ], 400);
    }

    /**
     * Get event details with meet link
     */
    public function show(string $eventId)
    {
        $event = $this->meetService->getEvent($eventId);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'event_id' => $event->getId(),
            'title' => $event->getSummary(),
            'description' => $event->getDescription(),
            'start_time' => $event->getStart()->getDateTime(),
            'end_time' => $event->getEnd()->getDateTime(),
            'meet_link' => $this->extractMeetLink($event),
            'html_link' => $event->getHtmlLink()
        ]);
    }

    /**
     * Update event
     */
    public function update(Request $request, string $eventId)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date_format:Y-m-d H:i:s',
            'end_time' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        $result = $this->meetService->updateEvent($eventId, $validated);

        if ($result['success']) {
            return response()->json([
                'message' => 'Event updated successfully',
                'data' => $result['event']
            ]);
        }

        return response()->json([
            'message' => 'Failed to update event',
            'error' => $result['error']
        ], 400);
    }

    /**
     * Delete event
     */
    public function destroy(string $eventId)
    {
        $result = $this->meetService->deleteEvent($eventId);

        if ($result['success']) {
            return response()->json([
                'message' => 'Event deleted successfully'
            ]);
        }

        return response()->json([
            'message' => 'Failed to delete event',
            'error' => $result['error']
        ], 400);
    }



    /**
     * Extract meet link from event
     */
    private function extractMeetLink($event): ?string
    {
        if ($event->getConferenceData() &&
            $event->getConferenceData()->getEntryPoints()) {

            foreach ($event->getConferenceData()->getEntryPoints() as $point) {
                if ($point->getEntryPointType() === 'video') {
                    return $point->getUri();
                }
            }
        }

        return null;
    }
}
