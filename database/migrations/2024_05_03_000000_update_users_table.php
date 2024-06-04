<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title_before')->nullable()->after('id');
            $table->string('surname')->nullable()->after('name');
            $table->string('title_after')->nullable()->after('surname');
            $table->string('street')->nullable()->after('title_after');
            $table->string('street_number')->nullable()->after('street');
            $table->string('city')->nullable()->after('street_number');
            $table->string('psc')->nullable()->after('city');
            $table->text('country')->nullable()->after('psc');
            $table->string('more_info')->nullable()->after('country');

            $table->string('phone_number')->nullable()->after('email');

            $table->boolean('investor')->default(false);
            $table->boolean('advertiser')->default(false);
            $table->boolean('real_estate_broker')->default(false);
            $table->enum('check_status', ['not_verified', 'waiting', 're_verified', 'verified'])->default('not_verified');
            $table->boolean('show_check_status')->default(false);

            $table->text('notice')->nullable();
            $table->text('investor_info')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->text('last_verified_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title_before',
                'surname',
                'title_after',
                'street',
                'street_number',
                'city',
                'psc',
                'country',
                'more_info',

                'phone_number',

                'investor',
                'advertiser',
                'real_estate_broker',
                'check_status',
                'show_check_status',
                'newsletters',
            ]);
        });
    }
};
