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
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('project_id');
            $table->text('filepath');
            $table->string('filename');
            $table->integer('order')->default(0);
            $table->boolean('public')->default(false);
            $table->string('folder')->nullable();
            $table->text('description')->nullable();

            $table->foreign('project_id')->references('id')->on('projects')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
