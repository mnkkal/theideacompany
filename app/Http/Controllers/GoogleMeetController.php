<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Laracasts\Flash\Flash;
use App\Models\User;
use App\Models\ZoomMeeting;
use App\Queries\MeetingDataTable;
use App\Repositories\MeetingRepository;
use Auth;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\App as FacadesApp;

class GoogleMeetController extends Controller
{
 
    public function createGoogleMeetEvent(Request $request)
    {
        $data = $request->all();
        $client = new Client();
        $client->setApplicationName('Google Meet Integration');
        $client->setScopes(Calendar::CALENDAR);
        $client->setAuthConfig(storage_path('app/google_credentials.json')); // Path to credentials
        $client->setAccessType('offline');

        // Authenticate the user (OAuth flow should be implemented here for first-time users)
        if (session()->has('access_token')) {
            $client->setAccessToken(session('access_token'));

            // Refresh the token if expired
            if ($client->isAccessTokenExpired()) {
                $refreshToken = $client->getRefreshToken();
                $client->fetchAccessTokenWithRefreshToken($refreshToken);
                session(['access_token' => $client->getAccessToken()]);
            }
        } else {
            // Redirect to OAuth consent screen
            return redirect()->route('oauthCallback');
        }

        // Create Google Calendar service
        $service = new Calendar($client);

        // Define the event
        $event = new Event([
            'summary' => $data['topic']??"New Meeting",
            'description' =>$data['agenda']?? "New Meeting" ,
            'start' => new EventDateTime([
                'dateTime' =>\Carbon\Carbon::parse($data['start_time'])->format('Y-m-d\TH:i:s'),
                'timeZone' => 'Asia/Kolkata',
            ]),
            'end' => new EventDateTime([
                'dateTime' =>  \Carbon\Carbon::parse($data['start_time'])->addMinutes($data['duration'] ?? 30)->format('Y-m-d\TH:i:s'),
                'timeZone' => 'Asia/Kolkata',
            ]),
            'conferenceData' => [
                'createRequest' => [
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet'
                    ],
                    'requestId' => 'unique-request-id-' . uniqid()
                ]
            ]
        ]);

        // Insert event into primary calendar
        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);
        
        if(isset($event) && isset($event->hangoutLink) && $event->hangoutLink != null){
            $data['time_zone'] = getTimeZone()[$data['time_zone']];
            $data['password'] ='12345678';
            $data['meeting_id'] = $event->hangoutLink;
            $data['meta'] =null;
            $data['created_by'] = Auth::id();
            $data['start_time'] = \Carbon\Carbon::parse($event->start->dateTime)->format('Y-m-d H:i:s');

            $zoomModel = ZoomMeeting::create($data);
            $zoomModel->members()->sync($data['members']);
        }
        Flash::success(__('messages.new_keys.meeting_saved'));

        return redirect('/dashboard');
        // Display event link
        //return response()->json(['event_link' => $event->htmlLink]);
    }
    public function oauthCallback()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google_credentials.json'));
        $client->setRedirectUri('https://admin.theideacompany.io/callback');
        $client->setScopes(Calendar::CALENDAR);

        if (request()->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode(request('code'));
            session(['access_token' => $token]);
            return redirect('/create-gmeet');
        } else {
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }
    }
}
