<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('subscription_plan_id')->nullable()->index(); 
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->double('plan_amount', 8, 2)->default(0.00)->index(); 
            $table->integer('plan_frequency')->default(1)->index(); 
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->index(); 
            $table->dateTime('trial_ends_at')->nullable()->index(); 
            $table->unsignedBigInteger('sms_limit')->default(0); 
            $table->tinyInteger('status')->default(0)->index(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
