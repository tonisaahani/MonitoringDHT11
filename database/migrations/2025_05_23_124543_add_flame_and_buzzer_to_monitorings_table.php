<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->string('flame')->nullable(); // kolom untuk status api
            $table->string('buzzer')->nullable(); // kolom untuk status buzzer
        });
    }

    public function down()
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropColumn('flame');
            $table->dropColumn('buzzer');
        });
    }

};
