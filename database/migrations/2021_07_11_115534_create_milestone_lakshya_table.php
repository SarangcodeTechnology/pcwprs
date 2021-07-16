<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneLakshyaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('milestone_lakshya', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('aayojana_id')->nullable();
            $table->unsignedBigInteger('kaaryalaya_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('kriyakalap_code')->nullable();
            $table->string('name')->nullable();
            $table->string('kharcha_sirsak')->nullable();
            $table->string('ikai')->nullable();
            $table->string('kharcha_prakar')->nullable();
            $table->string('milestone_id')->nullable();
            $table->string('milestone_name')->nullable();
            $table->float('pariman')->nullable();
            $table->float('shrawan_lakshya_pariman')->nullable();
            $table->float('bhadra_lakshya_pariman')->nullable();
            $table->float('ashoj_lakshya_pariman')->nullable();
            $table->float('kaartik_lakshya_pariman')->nullable();
            $table->float('mangsir_lakshya_pariman')->nullable();
            $table->float('paush_lakshya_pariman')->nullable();
            $table->float('magh_lakshya_pariman')->nullable();
            $table->float('falgun_lakshya_pariman')->nullable();
            $table->float('chaitra_lakshya_pariman')->nullable();
            $table->float('baisakh_lakshya_pariman')->nullable();
            $table->float('jestha_lakshya_pariman')->nullable();
            $table->float('ashar_lakshya_pariman')->nullable();
            $table->string('kaifiyat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->dropIfExists('milestone_lakshya');
    }
}
