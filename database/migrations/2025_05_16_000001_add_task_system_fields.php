<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_task_completed_at')->nullable();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->integer('daily_tasks_limit')->default(0);
            $table->decimal('daily_reward', 15, 2)->default(0);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_active')->default(true);
        });

        Schema::create('user_task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->decimal('reward_amount', 15, 2);
            $table->date('completion_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_task_completions');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['title', 'video_url', 'is_active']);
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['daily_tasks_limit', 'daily_reward']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_task_completed_at');
        });
    }
};
