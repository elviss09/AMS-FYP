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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('reminded_1day')->default(false);
            $table->boolean('reminded_3days')->default(false);
            $table->boolean('reminded_1week')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('reminded_1day')->default(false);
            $table->boolean('reminded_3days')->default(false);
            $table->boolean('reminded_1week')->default(false);
        });
    }
};
