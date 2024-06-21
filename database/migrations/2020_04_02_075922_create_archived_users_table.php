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
        Schema::create('archived_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('owner_id');
            $table->string('owner_type');
            $table->unsignedInteger('archived_by');
            $table->timestamps();

            $table->foreign('archived_by')
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
        Schema::dropIfExists('archived_users');
    }
};
