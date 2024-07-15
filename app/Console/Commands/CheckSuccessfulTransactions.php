<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class CheckSuccessfulTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-successful-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('status', 'successful')->where('processed', false)->get();

        foreach ($transactions as $transaction) {
            // Check if transaction corresponds to a subscription plan
            if ($transaction->subscription_plan_id) {
                $UserSubscription = UserSubscription::create([
                    'user_id' => $transaction->user_id,
                    'subscription_plan_id' => $transaction->subscription_plan_id,
                    'transaction_id' => $transaction->id,
                    'plan_amount' => $transaction->amount,
                    'plan_frequency' => 1, // Assuming monthly
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addMonth(),
                    'sms_limit' => 100, 
                    'status' => 1, 
                ]);

                
                $user = User::find($transaction->user_id);
                $user->givePermissionTo('manage_conversations');

                // Mark the transaction as processed
                $transaction->processed = true;
                $transaction->save();
            }
        }

        $this->info('Checked transactions and updated subscriptions.');
    }
}
