<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('active_gateway')->default('valorionpay'); // 'valorionpay' or 'bitflow'
            $table->string('bitflow_client_id')->nullable();
            $table->string('bitflow_client_secret')->nullable();
            $table->string('bitflow_public_key')->nullable();
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['active_gateway', 'bitflow_client_id', 'bitflow_client_secret', 'bitflow_public_key']);
        });
    }
};
