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
        $users = \App\Models\User::whereNotNull('player_id')->orWhere('player_id', '!=', '')->get();
        foreach ($users as $user) {
            $exists = \App\Models\UserDevice::whereUserId($user->id)->wherePlayerId($user->player_id)->first();

            if ($exists) {
                continue;
            }

            \App\Models\UserDevice::create([
                'user_id' => $user->id,
                'player_id' => $user->player_id,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new', function (Blueprint $table) {
            //
        });
    }
};
