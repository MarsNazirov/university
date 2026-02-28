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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->string('number')->unique();
            $table->integer('floor');
            $table->string('type');
            $table->decimal('price')->default(0);
            $table->integer('beds_count')->default(1);
            $table->string('status')->default('available');

            $table->text('description')->nullable();
            $table->string('photo')->nullable();

            $table->index('floor');
            $table->index('status');
            $table->index(['beds_count', 'status']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
