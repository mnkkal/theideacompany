<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateReportUserRequest;
use App\Repositories\ReportedUserRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class ReportUserController
 */
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

    public function store(CreateReportUserRequest $request): JsonResponse
    {
        $this->reportedUserRepo->createReportedUser($request->all());

        return $this->sendSuccess(__('messages.new_keys.user_reported'));
    }
}
