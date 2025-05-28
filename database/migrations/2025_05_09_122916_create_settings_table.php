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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('system_name')->nullable();
            $table->longText('system_title')->nullable();
            $table->longText('system_address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('opening_hour')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
