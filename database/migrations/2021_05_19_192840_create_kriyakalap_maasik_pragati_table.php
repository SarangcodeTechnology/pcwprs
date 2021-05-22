<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKriyakalapMaasikPragatiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('kriyakalap_maasik_pragati', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('kriyakalap_lakshya_id');
            $table->integer('mahina_id');
            $table->float('pariman')->nullable();
            $table->float('kharcha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->dropIfExists('kriyakalap_maasik_pragati');
    }
}
