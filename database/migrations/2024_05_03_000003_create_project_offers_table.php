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
        Schema::create('project_offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->foreignId('project_id');
            $table->integer('price');

            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('project_id')->references('id')->on('projects')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_offers');
    }
};
