<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKaaryalayaIdInKriyakalaapMaasikPragati extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function __construct()
    {
        Schema::connection("pcwprs_data");
    }

    public function up()
    {
        Schema::connection("pcwprs_data")->table('kriyakalap_maasik_pragati', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
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
            $table->dropColumn('user_id');
        });
    }
}
