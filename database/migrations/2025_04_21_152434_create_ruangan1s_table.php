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
        Schema::create('ruangan1s', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('faculty');
            $table->string('building');
            $table->string('room')->nullable()->default("Belum Diisi");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan1s');
    }
};
