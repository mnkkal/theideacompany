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
        Schema::create('job_post', function (Blueprint $table) {
            $table->id();
            $table->string('job_post');
            $table->string('email');
            $table->string('company_name');
            $table->json('job_type');
            $table->string('doj');
            $table->date('apply_by');
            $table->string('salary');
            $table->string('hiring_from');
            $table->text('about_company');
            $table->text('about_job');
            $table->string('who_can_apply');
            $table->string('skill_required');
            $table->string('add_perks_of_job');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post');
    }
};

