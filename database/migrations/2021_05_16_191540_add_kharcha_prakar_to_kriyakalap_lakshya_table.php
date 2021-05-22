<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKharchaPrakarToKriyakalapLakshyaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->table('kriyakalap_lakshya', function (Blueprint $table) {
            $table->string('kharcha_prakar')->nullable();
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
            $table->removeColumn('kharcha_prakar');
        });
    }
}
