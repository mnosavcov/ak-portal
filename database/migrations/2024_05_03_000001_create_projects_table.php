<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->enum('user_account_type', [
                    'advertiser',
                    'real-estate-broker',
                ]
            );
            $table->enum('type', ['auction', 'fixed-price', 'offer-the-price'])->nullable();
            $table->foreignId('subcategory_id')->nullable();
            $table->enum('status', [
                'draft',
                'send',
                'prepared',
                'confirm',
                'reminder',
                'publicated',
                'evaluation',
                'finished',
            ])->default('draft');
            $table->datetime('publicated_at')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('about')->nullable();
            $table->text('short_info')->nullable();
            $table->text('actual_state')->nullable();
            $table->text('user_reminder')->nullable();
            $table->integer('price')->nullable();
            $table->integer('minimum_principal')->nullable();
            $table->integer('min_bid_amount')->nullable();
            $table->string('subject_offer');
            $table->string('location_offer');
            $table->string('country');
            $table->text('page_url')->nullable();
            $table->text('page_title')->nullable();
            $table->text('page_description')->nullable();
            $table->string('representation_type')->nullable();
            $table->date('representation_end_date')->nullable();
            $table->boolean('representation_indefinitely_date')->nullable();
            $table->boolean('representation_may_be_cancelled')->nullable();
            $table->boolean('exclusive_contract')->default(false);
            $table->boolean('details_on_request')->default(true);
            $table->string('map_lat_lng')->nullable();
            $table->integer('map_zoom')->default(18);
            $table->string('map_title')->nullable();
            $table->string('map_type')->default('terrain');

            $table->unique('page_url');

            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->foreign('subcategory_id')->references('id')->on('categories')->constrained();
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
