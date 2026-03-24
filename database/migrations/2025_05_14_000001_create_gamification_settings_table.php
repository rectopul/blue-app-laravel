<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gamification_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('required_referrals');
            $table->string('page_name'); // e.g., 'dashboard', 'user.team', etc.
            $table->decimal('bonus_reward', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gamification_settings');
    }
};
