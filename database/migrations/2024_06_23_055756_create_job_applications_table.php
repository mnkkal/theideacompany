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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('experience');
            $table->string('notice_period');
            $table->string('current_job');
            $table->string('resume')->nullable();
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job_post')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
