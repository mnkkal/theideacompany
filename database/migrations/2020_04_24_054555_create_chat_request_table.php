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
        Schema::create('chat_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('from_id')->nullable();
            $table->string('owner_id')->nullable();
            $table->string('owner_type')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('from_id')
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
        Schema::dropIfExists('chat_request');
    }
};
