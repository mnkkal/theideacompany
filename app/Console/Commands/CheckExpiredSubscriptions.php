<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserSubscription;

use App\Models\User;
use Carbon\Carbon;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expirations';
    protected $description = 'Check for expired subscriptions and remove user permissions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Fetch subscriptions that have expired
        $subscriptions = UserSubscription::where('ends_at', '<', Carbon::now())->where('status', 1)->get();

        foreach ($subscriptions as $subscription) {
            // Remove conversation role permission
            $user = User::find($subscription->user_id);
            $user->revokePermissionTo('manage_conversations');

            // Update subscription status to expired
            $subscription->status = 0;
            $subscription->save();
        }

        $this->info('Checked expired subscriptions and updated user permissions.');
    }
}
