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
            $table->enum('type', ['auction', 'fixed-price', 'offer-the-price'])->nullable();
            $table->foreignId('subcategory_id')->nullable();
            $table->string('status')->default('draft');
            $table->date('end_date')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('about')->nullable();
            $table->text('short_info')->nullable();
            $table->text('actual_state')->nullable();
            $table->text('user_reminder')->nullable();
            $table->integer('price')->nullable();
            $table->integer('minimum_principal')->nullable();
            $table->string('subject_offer');
            $table->string('location_offer');
            $table->string('country');
            $table->string('representation_type')->nullable();
            $table->date('representation_end_date')->nullable();
            $table->boolean('representation_indefinitely_date')->nullable();
            $table->boolean('representation_may_be_cancelled')->nullable();
            $table->boolean('representation_may_be_cancelled')->nullable();
            $table->boolean('exclusive_contract')->default(false);
            $table->boolean('details_on_request')->default(true);

            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->constrained();
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
