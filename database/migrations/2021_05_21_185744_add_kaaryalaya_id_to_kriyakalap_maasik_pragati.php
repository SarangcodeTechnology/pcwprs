<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKaaryalayaIdToKriyakalapMaasikPragati extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection("pcwprs_data")->table('kriyakalap_maasik_pragati', function (Blueprint $table) {
            $table->unsignedInteger('kaaryalaya_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection("pcwprs_data")->table('kriyakalap_maasik_pragati', function (Blueprint $table) {
            $table->dropColumn('kaaryalaya_id');
        });
    }
}
