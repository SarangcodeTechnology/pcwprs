<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInKriyakalapLakshyaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->table('kriyakalap_lakshya', function (Blueprint $table) {
            $table->text('component')->nullable();
            $table->boolean('milestone')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->table('kriyakalap_lakshya', function (Blueprint $table) {
            $table->dropColumn(['milestone','component']);
        });
    }
}
