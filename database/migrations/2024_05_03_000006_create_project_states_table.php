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
        Schema::create('project_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->integer('order')->default(0);
            $table->string('title');
            $table->text('description');
            $table->enum('state', ['ok', 'no', 'partly']);

            $table->foreign('project_id')->references('id')->on('projects')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_states');
    }
};
