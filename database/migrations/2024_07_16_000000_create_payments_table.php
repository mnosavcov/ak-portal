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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('project_id')->nullable();
            $table->string('pohyb_id');
            $table->string('pokyn_id');
            $table->date('datum');
            $table->integer('castka');
            $table->string('mena');
            $table->string('protiucet');
            $table->string('protiucet_kodbanky');
            $table->string('protiucet_nazevbanky');
            $table->string('protiucet_nazevprotiuctu');
            $table->string('protiucet_uzivatelska_identifikace');
            $table->string('vs');
            $table->text('zprava_pro_prijemce');

            $table->unique(['pohyb_id']);

            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('project_id')->references('id')->on('projects')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
