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
        Schema::create('graves', function (Blueprint $table) {
            $table->id();
            $table->string('block_name');
            $table->string('grave_number');
            $table->string('buried_name')->nullable();
            $table->date('burial_date')->nullable();
            $table->string('heir_name')->nullable();
            $table->string('heir_contact')->nullable();
            $table->enum('status', ['available', 'occupied', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graves');
    }
};
