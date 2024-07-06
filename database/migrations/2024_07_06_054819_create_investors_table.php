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
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->text('problem_opportunity');
            $table->text('solution_technology');
            $table->text('current_stage');
            $table->string('product_demo')->nullable();
            $table->text('unique_value_proposition');
            $table->text('competitive_advantage');
            $table->text('target_customer_segment');
            $table->text('channels_strategies');
            $table->text('revenue_streams');
            $table->text('costs_expenditures');
            $table->text('plan_24_month');
            $table->text('why_applying');
            $table->string('pitch_deck')->nullable();
            $table->string('certificate_incorporation')->nullable();
            $table->integer('capital_required');
            $table->string('investment_preferred');
            $table->integer('equity_amount')->nullable();
            $table->integer('debt_amount')->nullable();
            $table->integer('equity_offered')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
