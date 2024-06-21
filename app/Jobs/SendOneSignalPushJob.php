<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OneSignal;

class SendOneSignalPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $playerIds;

    private $heading;

    private $message;

    private $options;

    /**
     * Create a new job instance.
     */
    public function __construct(array $playerIds, string $heading, bool $message, array $options = [])
    {
        $this->playerIds = $playerIds;
        $this->heading = $heading;
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendOneSignalPush($this->playerIds, $this->message, $this->heading, $this->options);
    }

    /**
     * @return void|bool
     */
    public function sendOneSignalPush(array $playerIds, string $message, string $headings, array $options = [])
    {
        $parameters = [
            'headings' => [
                'en' => $headings,
            ],
            'contents' => [
                'en' => isset($options['image']) ? 'Image Sent !' : $message,
            ],
            'chrome_web_icon' => isset($options['image']) ? $options['image'] : '',
            'include_player_ids' => $playerIds,
        ];
        $result = OneSignal::sendNotificationCustom($parameters);

        return true;
    }
}
