<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeetingRequest;
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
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\App as FacadesApp;


/**
 * Class MeetingController
 */
class MeetingController extends AppBaseController
{
    /**
     * @var MeetingRepository
     */
    private $meetingRepository;

    /**
     * MeetingController constructor.
     */
    public function __construct(MeetingRepository $meetingRepository)
    {
        $this->meetingRepository = $meetingRepository;
    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if (getLoggedInUser()->hasRole('Member')) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            return Datatables::of((new MeetingDataTable())->get())->make(true);
        }

        return view('meetings.index');
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View
    {
        $timeZones = getTimeZone();
        $users = User::where('id', '!=', Auth::id())->pluck('name', 'id');

        return view('meetings.create', compact('users', 'timeZones'));
    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function edit($meeting)
    {
        $timeZones = getTimeZone();
        $meeting = ZoomMeeting::with('members')->findOrFail($meeting);
        if ($meeting->status == ZoomMeeting::STATUS_FINISHED) {
            Flash::error(__('messages.new_keys.Sorry_not_update_finished_meeting'));

            return redirect()->route('meetings.index');
        }

        $meetingTimeZone = array_flip($timeZones)[$meeting->time_zone];
        $members = $meeting->members->pluck('id')->toArray();
        $users = User::where('id', '!=', Auth::id())->pluck('name', 'id');

        return view('meetings.edit', compact('users', 'meeting', 'timeZones', 'members', 'meetingTimeZone'));
    }

    public function store(CreateMeetingRequest $request): RedirectResponse
    {
      $create = $this->meetingRepository->store($request->all());

      if($create) {
        Flash::success(__('messages.new_keys.meeting_saved'));

      return redirect()->route('meetings.index');

      }else{

        return redirect()->back();
      }

    }

    public function update($meeting, CreateMeetingRequest $request)
    {

       $store = $this->meetingRepository->updateZoom($meeting, $request->all());
        if( $store){
            Flash::success(__('messages.new_keys.meeting_updated'));

            return redirect()->route('meetings.index');
        }else{

            return redirect()->back();
        }


    }

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|RedirectResponse
     *
     * @throws Exception
     */
    public function showMemberMeetings(Request $request)
    {
        if (! getLoggedInUser()->hasRole('Member')) {
            return redirect()->back();
        }

        if ($request->ajax()) {
            return Datatables::of((new MeetingDataTable())->get($member = true))->make(true);
        }

        return view('meetings.members_index');
    }

    public function destroy(ZoomMeeting $meeting): JsonResponse
    {
        $this->meetingRepository->deleteMeeting($meeting->id);

        return $this->sendSuccess(__('messages.new_keys.meeting_deleted'));
    }

    public function changeMeetingStatus(ZoomMeeting $meeting, $status): JsonResponse
    {
        $this->meetingRepository->changeMeetingStatus($meeting->id, $status);

        return $this->sendSuccess(__('messages.new_keys.meeting_updated'));
    }

    public function zoomConnect(Request $request)
    {
        // $userZoomCredential = UserZoomCredential::where('user_id', getLoggedInUserId())->first();
        if (empty(config('app.zoom_api_key'))) {

            return redirect()->back()->withErrors(__('messages.new_keys.add_credentials'));
        }
        $clientID = config('app.zoom_api_key');
        $callbackURL = config('app.zoom_callback');

        $url = "https://zoom.us/oauth/authorize?client_id=$clientID&response_type=code&redirect_uri=$callbackURL";

        return redirect($url);
    }

    public function zoomCallback(Request $request)
    {
        $zoomRepo = FacadesApp::make(MeetingRepository::class);
        $zoomRepo->connectWithZoom($request->get('code'));

        return redirect(route('meetings.index'));
    }
}
