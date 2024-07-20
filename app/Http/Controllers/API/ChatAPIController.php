<?php

namespace App\Http\Controllers\API;

use App\Events\UserEvent;
use App\Exceptions\ApiOperationFailedException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\SendMessageRequest;
use App\Models\ChatRequestModel;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ChatRepository;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ChatAPIController
 */
class ChatAPIController extends AppBaseController
{
    /** @var ChatRepository */
    private $chatRepository;

    /**
     * Create a new controller instance.
     */
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    /**
     * This function return latest conversations of users.
     */
    public function getLatestConversations(Request $request): JsonResponse
    {
        $input = $request->all();
        
        $conversations = $this->chatRepository->getLatestConversations($input);

        return $this->sendResponse(['conversations' => $conversations], 'Conversations retrieved successfully.');
    }

    /**
     * This function return latest conversations of users.
     */
    public function getArchiveConversations(Request $request): JsonResponse
    {
        $input = $request->all();
        $input['isArchived'] = 1;
        $conversations = $this->chatRepository->getLatestConversations($input);

        return $this->sendResponse(['conversations' => $conversations], 'Conversations retrieved successfully.');
    }

    /**
     * @throws Exception
     */
    public function sendMessage(SendMessageRequest $request): JsonResponse
    {
        $conversation = $this->chatRepository->sendMessage($request->all());

        return $this->sendResponse(['message' => $conversation], 'Message sent successfully.');
    }

    public function updateConversationStatus(Request $request): JsonResponse
    {
        $data = $this->chatRepository->markMessagesAsRead($request->all());

        return $this->sendResponse($data, 'Status updated successfully.');
    }

    /**
     * @throws ApiOperationFailedException
     */
    public function addAttachment(Request $request): JsonResponse
    {
        $files = $request->file('file');

        foreach ($files as $file) {
            $fileData['attachment'] = $this->chatRepository->addAttachment($file);
            $extension = $file->getClientOriginalExtension();
            $fileData['message_type'] = $this->chatRepository->getMessageTypeByExtension($extension);
            $fileData['file_name'] = $file->getClientOriginalName();
            $fileData['unique_code'] = uniqid();
            $data['data'][] = $fileData;
        }
        $data['success'] = true;

        return $this->sendData($data);
    }

    /**
     * @param  int|string  $id
     */
    public function deleteConversation($id): JsonResponse
    {
        if (is_string($id) && ! is_numeric($id)) {
            $this->chatRepository->deleteGroupConversation($id);
        } else {
            $this->chatRepository->deleteConversation($id);
        }

        return $this->sendSuccess('Conversation deleted successfully.');
    }

    public function deleteMessage(Conversation $conversation, Request $request): JsonResponse
    {
        $deleteMessageTime = config('configurable.delete_message_time');
        if ($conversation->time_from_now_in_min > $deleteMessageTime) {
            return $this->sendError(__('messages.new_keys.not_delete_older_message').' '.$deleteMessageTime.' '. __('messages.new_keys.minutes'), 422);
        }

        if ($conversation->from_id != getLoggedInUserId()) {
            return $this->sendError('You can not delete this message.', 403);
        }

        $previousMessageId = $request->get('previousMessageId');
        $previousMessage = $this->chatRepository->find($previousMessageId);
        $this->chatRepository->deleteMessage($conversation->id);

        return $this->sendResponse(['previousMessage' => $previousMessage], 'Message deleted successfully.');
    }

    public function show(Conversation $conversation): JsonResponse
    {
        return $this->sendResponse($conversation->toArray(), 'Conversation retrieved successfully');
    }

    /**
     * @throws Exception
     */
    public function deleteMessageForEveryone(Conversation $conversation, Request $request): JsonResponse
    {
        $deleteMessageTime = config('configurable.delete_message_for_everyone_time');
        if ($conversation->time_from_now_in_min > $deleteMessageTime) {
            return $this->sendError(__('messages.new_keys.not_delete_older_message').' '.$deleteMessageTime.' '. __('messages.new_keys.minutes'), 422);
        }

        if ($conversation->from_id != getLoggedInUserId()) {
            return $this->sendError('You can not delete this message.', 403);
        }

        $conversation->delete();

        $previousMessageId = $request->get('previousMessageId');
        $previousMessage = $this->chatRepository->find($previousMessageId);
        unset($previousMessage->replayMessage);

        broadcast(new UserEvent(
            [
                'id' => $conversation->id,
                'type' => User::MESSAGE_DELETED,
                'from_id' => $conversation->from_id,
                'previousMessage' => $previousMessage,
            ], $conversation->to_id))->toOthers();

        return $this->sendResponse(['previousMessage' => $previousMessage], 'Message deleted successfully.');
    }

    /**
     * @throws Exception
     */
    public function sendChatRequest(SendMessageRequest $request): JsonResponse
    {
        $isRequestSend = $this->chatRepository->sendChatRequest($request->all());
        if ($isRequestSend) {
            return $this->sendSuccess(__('messages.new_keys.chat_request_send_successfully'));
        }

        return $this->sendError(__('messages.new_keys.chat_request_has_been_sent'));
    }

    public function acceptChatRequest(Request $request): JsonResponse
    {
        $chatRequestModel = ChatRequestModel::whereId($request->id)->first();
        $chatRequestModel->status = ChatRequestModel::STATUS_ACCEPTED;
        $chatRequestModel->save();

        $input = $chatRequestModel->toArray();
        $input['message'] = $chatRequestModel->receiver->name.' has accepted your chat request.';
        $this->chatRepository->sendAcceptDeclineChatRequestNotification($input, User::CHAT_REQUEST_ACCEPTED);

        return $this->sendResponse($chatRequestModel, __('messages.new_keys.chat_request_accepted_successfully'));
    }

    public function declineChatRequest(Request $request): JsonResponse
    {
        $chatRequestModel = ChatRequestModel::find($request->id);
        $chatRequestModel->status = ChatRequestModel::STATUS_DECLINE;
        $chatRequestModel->save();

        Conversation::whereFromId($chatRequestModel->from_id)->whereToId($chatRequestModel->owner_id)->update(['status' => 1]);

        $input = $chatRequestModel->toArray();
        $input['message'] = $chatRequestModel->receiver->name.' has declined your chat request.';
        $this->chatRepository->sendAcceptDeclineChatRequestNotification($input);

        return $this->sendResponse($chatRequestModel, __('messages.new_keys.you_declined_given_user_request'));
    }

    /**
     * @throws ApiOperationFailedException
     */
    public function imageUpload(Request $request): JsonResponse
    {
        $input = $request->all();
        $images = $input['images'];
        unset($input['images']);
        $input['from_id'] = Auth::id();
        $input['to_type'] = Conversation::class;
        $conversation = [];
        foreach ($images as $image) {
            $fileName = Conversation::uploadBase64Image($image, Conversation::PATH);
            $input['message'] = $fileName;
            $input['status'] = 0;
            $input['message_type'] = 1;
            $input['file_name'] = $fileName;
            $conversation[] = $this->chatRepository->sendMessage($input);
        }

        return $this->sendResponse($conversation, 'File uploaded');
    }
}
