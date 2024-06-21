<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \App\Models\Conversation::whereNull('from_id')->orWhere('from_id', '')->delete();

        \App\Models\Conversation::whereDoesntHave('receiver')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
