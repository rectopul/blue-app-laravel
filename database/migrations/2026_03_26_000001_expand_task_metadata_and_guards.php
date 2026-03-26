<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (!Schema::hasColumn('tasks', 'watch_seconds')) {
                $table->unsignedInteger('watch_seconds')->default(30)->after('video_url');
            }

            if (!Schema::hasColumn('tasks', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('watch_seconds');
            }

            if (!Schema::hasColumn('tasks', 'icon')) {
                $table->string('icon')->default('play_circle')->after('sort_order');
            }
        });

        DB::table('tasks')->whereNull('watch_seconds')->update(['watch_seconds' => 30]);
        DB::table('tasks')->whereNull('sort_order')->update(['sort_order' => 0]);
        DB::table('tasks')->whereNull('icon')->update(['icon' => 'play_circle']);

        $duplicateIds = DB::table('user_task_completions')
            ->select(DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'task_id', 'completion_date')
            ->pluck('keep_id')
            ->all();

        if (!empty($duplicateIds)) {
            DB::table('user_task_completions')
                ->whereNotIn('id', $duplicateIds)
                ->delete();
        }

        Schema::table('user_task_completions', function (Blueprint $table) {
            $table->unique(['user_id', 'task_id', 'completion_date'], 'user_task_completion_daily_unique');
        });
    }

    public function down()
    {
        Schema::table('user_task_completions', function (Blueprint $table) {
            $table->dropUnique('user_task_completion_daily_unique');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['description', 'watch_seconds', 'sort_order', 'icon']);
        });
    }
};
