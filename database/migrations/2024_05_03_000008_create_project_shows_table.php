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
        Schema::create('project_shows', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->foreignId('project_id');
            $table->boolean('favourite')->default(false);
            $table->integer('price')->nullable();
            $table->boolean('offer')->default(false);
            $table->dateTime('offer_time')->nullable();
            $table->boolean('principal_paid')->default(false);
            $table->boolean('winner')->default(false);
            $table->boolean('showed')->default(false);
            $table->integer('details_on_request')->default(0);
            $table->dateTime('details_on_request_time')->nullable();

            $table->unique(['user_id', 'project_id']);

            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('project_id')->references('id')->on('projects')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_shows');
    }
};
