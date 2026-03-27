<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'daily_tasks_limit')) {
                $table->integer('daily_tasks_limit')->default(0);
            }
            if (!Schema::hasColumn('packages', 'daily_reward')) {
                $table->decimal('daily_reward', 15, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['daily_tasks_limit', 'daily_reward']);
        });
    }
};
