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
        Schema::create('project_contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type', ['questions', 'actualities']);
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('parent_id')->nullable()->constrained('project_contents');
            $table->foreignId('user_id')->constrained('users');
            $table->text('content');
            $table->integer('level')->default(0);
            $table->json('files')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->text('not_confirmed_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_contents');
    }
};
