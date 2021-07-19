<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestonePragatiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('milestone_pragati', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('milestone_lakshya_id');
            $table->integer('mahina_id');
            $table->integer('user_id');
            $table->integer('kaaryalaya_id');
            $table->float('prarambhik_karya_suru_pragati')->nullable();
            $table->float('prarambhik_karya_jari_pragati')->nullable();
            $table->float('prarambhik_karya_sampanna_pragati')->nullable();
            $table->float('karyakram_karyanayan_suru_pragati')->nullable();
            $table->float('karyakram_karyanayan_jari_pragati')->nullable();
            $table->float('karyakram_karyanayan_sampanna_pragati')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milestone_pragati');
    }
}
