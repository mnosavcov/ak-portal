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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->enum('type', ['auction', 'fixed_price', 'offer_the_price'])->nullable();
            $table->string('status')->default('draft');
            $table->date('end_date')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('img_filename');
            $table->integer('price')->nullable();
            $table->integer('subject_offer')->nullable();
            $table->integer('location_offer')->nullable();
            $table->integer('country')->nullable();
            $table->integer('representation')->nullable();
            $table->date('contract_validity')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
