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
            $table->string('user_account_type');
            $table->enum('type', ['auction', 'fixed_price', 'offer_the_price'])->nullable();
            $table->string('status')->default('draft');
            $table->date('end_date')->nullable();
            $table->string('title');
            $table->text('description');
            $table->integer('price')->nullable();
            $table->string('subject_offer');
            $table->string('location_offer');
            $table->string('country');
            $table->string('representation_type')->nullable();
            $table->date('representation_end_date')->nullable();
            $table->boolean('representation_indefinitely_date')->nullable();
            $table->boolean('representation_may_be_cancelled')->nullable();

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
