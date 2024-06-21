<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class NotificationController
 */
class NotificationController extends AppBaseController
{
    /** @var NotificationRepository */
    private $notificationRepo;

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepo = $notificationRepository;
    }

    public function readNotification(Notification $notification): JsonResponse
    {
        $this->notificationRepo->readNotification($notification->id);

        return $this->sendResponse($notification, __('messages.new_keys.message_read'));
    }

    public function readAllNotification(): JsonResponse
    {
        $messageSenderIds = $this->notificationRepo->readAllNotification();

        return $this->sendResponse(['sender_ids' => $messageSenderIds], __('messages.new_keys.read_all_messages'));
    }
}
