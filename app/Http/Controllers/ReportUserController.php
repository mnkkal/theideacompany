<?php

namespace App\Http\Controllers;

use App\Models\ReportedUser;
use App\Queries\ReportedUserDataTable;
use App\Repositories\ReportedUserRepository;
use DataTables;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportUserController extends AppBaseController
{
    /**
     * @var ReportedUserRepository
     */
    private $reportedUserRepo;

    /**
     * ReportUserController constructor.
     */
    public function __construct(ReportedUserRepository $reportedUserRepository)
    {
        $this->reportedUserRepo = $reportedUserRepository;
    }

    /**
     * Display a listing of the User.
     *
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new ReportedUserDataTable())->get($request->only(['is_active_filter'])))->make(true);
        }

        return view('reported_users.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(ReportedUser $reportedUser): JsonResponse
    {
        $this->reportedUserRepo->delete($reportedUser->id);

        return $this->sendSuccess(__('messages.new_keys.reported_user_deleted'));
    }

    public function show(ReportedUser $reportedUser): JsonResponse
    {
        $reportedUser->load(['reportedBy', 'reportedTo']);

        return $this->sendData($reportedUser);
    }
}
