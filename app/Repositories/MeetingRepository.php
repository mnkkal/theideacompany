<?php

namespace App\Repositories;

use App\Models\ZoomMeeting;
use App\Models\ZoomOAuth;
use App\Traits\ZoomMeetingTrait;
use Auth;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class MeetingRepository
 */
class MeetingRepository extends BaseRepository
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_SCHEDULE = 2;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
    ];

    /**
     * Return searchable fields.
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return ZoomMeeting::class;
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $zoom = $this->createZoomMeeting($data);
            $startTime = $data['start_time'];
            $data['time_zone'] = getTimeZone()[$data['time_zone']];
            $data['password'] = $zoom['password'];
            $data['meeting_id'] = $zoom['id'];
            $data['meta'] = $zoom;
            $data['created_by'] = Auth::id();
            $data['start_time'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');

            $zoomModel = ZoomMeeting::create($data);

            $zoomModel->members()->sync($data['members']);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            Flash::error($exception->getMessage());
        }
    }

    public function updateZoom($id, $data)
    {
        try {

            DB::beginTransaction();
            $zoomMeeting = ZoomMeeting::findOrFail($id);

            $startTime = $data['start_time'];
            $data['time_zone'] = getTimeZone()[$data['time_zone']];
            $zoom = $this->updateZoomMeeting($data,$zoomMeeting->meeting_id);
            $data['created_by'] = Auth::id();
            $data['start_time'] = Carbon::parse($startTime)->format('Y-m-d H:i:s');

            $zoomModel = $zoomMeeting->update($data);

            $zoomMeeting->members()->sync($data['members']);
            DB::commit();

            return true;
        } catch (Exception $exception) {

            DB::rollBack();
            Flash::error($exception->getMessage());
        }
    }

    public function deleteMeeting($id)
    {
        try {
            $zoomMeeting = ZoomMeeting::findOrFail($id);
            $this->destroyZoomMeeting($zoomMeeting->meeting_id);
            $zoomMeeting->members()->detach();
            $zoomMeeting->delete();

            return true;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function changeMeetingStatus(int $id, $status): bool
    {
        $meeting = ZoomMeeting::findOrFail($id);
        $meeting->update(['status' => $status]);

        return true;
    }


    public function connectWithZoom($code)
    {
        if (config('app.zoom_api_secret') && config('app.zoom_api_key') ) {
            $clientID = config('app.zoom_api_key');
            $secret = config('app.zoom_api_secret');
        } else {
            return Flash::error(__('messages.new_keys.add_credentials'));
        }

        $client = new Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            'headers' => [
                'Authorization' => 'Basic '.base64_encode($clientID.':'.$secret),
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('app.zoom_callback'),
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);

        $exist = ZoomOAuth::where('user_id', Auth::id())->first();
        if (! $exist) {
            ZoomOAuth::create([
                'user_id' => Auth::id(),
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
        } else {
            $exist->update([
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]);
        }

        return true;
    }

    public function updateZoomMeeting($data, $meetingId)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);

        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            $response = $client->request('PATCH', 'v2/meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
                'json' => [
                    'topic' => $data['topic'],
                    'type' => 2,
                    'start_time' => $this->toZoomTimeFormat($data['start_time']),
                    'duration' => $data['duration'],
                    'agenda' => (! empty($data['description'])) ? $data['description'] : null,
                    'password' => '123456',
                    'settings' => [
                        'host_video' => $data['host_video'] == 1 ? true : false,
                        'participant_video' => $data['participant_video'] == 1 ? true : false,
                        'waiting_room' => true,
                    ],
                ],
            ]);

            $data = json_decode($response->getBody());

            return (array) $data;
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                throw new UnprocessableEntityHttpException(__('messages.new_keys.invalid_access_token'));
            }
            if ($e->getCode() == 0) {
                throw new UnprocessableEntityHttpException(__('messages.new_keys.you_have_to_connect_with_zoom'));
            }
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function createZoomMeeting($data)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            $response = $client->request('POST', '/v2/users/me/meetings', [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
                'json' => [
                    'topic' => $data['agenda'],
                    'type' => 2,
                    'start_time' => $this->toZoomTimeFormat($data['start_time']),
                    'duration' => $data['duration'],
                    'agenda' => (! empty($data['agenda'])) ? $data['agenda'] : null,
                    'password' => '123456',
                    'settings' => [
                        'host_video' => ($data['host_video'] == 1) ? true : false,
                        'participant_video' => ($data['participant_video'] == 1) ? true : false,
                        'waiting_room' => true,
                    ],
                ],
            ]);
            $data = json_decode($response->getBody());
            return (array) $data;
        } catch (\Exception $e) {
            if (401 == $e->getCode()) {
                if (! isset($clientID)) {
                    throw new UnprocessableEntityHttpException('Please connect to zoom.');
                }
                $userZoomCredential = UserZoomCredential::where('user_id', getLoggedInUserId())->first();
                if (! isset($userZoomCredential)) {

                    throw new UnprocessableEntityHttpException(__('messages.new_keys.add_credentials'));
                }
                $refresh_token = $zoomOAuth->refresh_token;
                $client = new Client(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic '.base64_encode($clientID.':'.$secret),
                    ],
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refresh_token,
                    ],
                ]);
                $zoomOAuth->update(['refresh_token' => $response->getBody()]);

                $this->createZoomMeeting($data);
            } else {
                if ($e->getCode() == 401) {
                    throw new UnprocessableEntityHttpException(__('messages.new_keys.invalid_access_token'));
                }
                if ($e->getCode() == 0) {
                    throw new UnprocessableEntityHttpException(__('messages.new_keys.you_have_to_connect_with_zoom'));
                }
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }
    }

    public function destroyZoomMeeting($meetingId)
    {
        $clientID = config('app.zoom_api_key');
        $secret = config('app.zoom_api_secret');

        $client = new Client(['base_uri' => 'https://api.zoom.us']);

        $zoomOAuth = ZoomOAuth::where('user_id', Auth::id())->first();

        try {
            $response = $client->request('DELETE', '/v2/meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => 'Bearer '.$zoomOAuth->access_token,
                ],
            ]);

            $data = json_decode($response->getBody());

            return $data;
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                throw new UnprocessableEntityHttpException(__('messages.new_keys.invalid_access_token'));
            }
            if ($e->getCode() == 400) {
                throw new UnprocessableEntityHttpException(__('messages.new_keys.you_cannot_delete_this_meeting_progress'));
            }
            if ($e->getCode() == 0) {
                throw new UnprocessableEntityHttpException(__('messages.new_keys.you_have_to_connect_with_zoom'));
            }
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

}
