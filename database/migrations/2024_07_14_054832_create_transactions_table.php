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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref',1024)->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('subscription_plan_id')->nullable()->index();
            $table->double('amount', 8, 2);
            $table->string('status'); // e.g., 'pending', 'successful', 'failed'
            $table->boolean('processed')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
