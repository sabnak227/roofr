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
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('code');
            $table->string('type')->default("normal");
            $table->string('vehicle_type')->nullable();
            $table->timestamps();
            $table->index(['group_id', 'code']);
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop("parking_spots");
    }
};
