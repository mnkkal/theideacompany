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
        Schema::create('zoom_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('topic');
            $table->dateTime('start_time');
            $table->string('duration');
            $table->boolean('host_video');
            $table->boolean('participant_video');
            $table->text('agenda');
            $table->unsignedInteger('created_by');
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_meetings');
    }
};
