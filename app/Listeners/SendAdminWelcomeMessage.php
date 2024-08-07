<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserJoinedChat;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use App\Models\ChatRequestModel;
use App\Events\UserEvent;
use App\Repositories\NotificationRepository;

class SendAdminWelcomeMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserJoinedChat $event)
    {
        $user = $event->user;

        $admin = User::role('Admin')->first();
        

       
        $messageContent = "Welcome to the chat! How can we assist you today?";

       
        // ChatRequestModel::create([
        //     'from_id' => $admin->id,
        //     'owner_id' => $user->id,
        //     'owner_type' => User::class,
        // ]);

        $conversation = Conversation::create([
            'from_id' => $admin->id,
            'to_id' => $user->id,
            'to_type' => Conversation::class,
            'message' => $messageContent,
            'status' => 0,
            'message_type' => 0,
        ]);
        


        return true;
    }
}
