<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKriyakalapLakshyaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('kriyakalap_lakshya', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('aayojana_id')->nullable();
            $table->string('kriyakalap_code')->nullable();
            $table->string('name')->nullable();
            $table->string('kharcha_sirsak')->nullable();
            $table->string('ikai')->nullable();
            $table->float('aayojana_kul_kriyakalap_pariman')->nullable();
            $table->float('aayojana_kul_kriyakalap_vaar')->nullable();
            $table->float('aayojana_kul_kriyakalap_laagat')->nullable();
            $table->float('gata_aarthik_barsa_sammako_pariman')->nullable();
            $table->float('gata_aarthik_barsa_sammako_vaar')->nullable();
            $table->float('gata_aarthik_barsa_sammako_laagat')->nullable();
            $table->float('baarsik_lakshya_pariman')->nullable();
            $table->float('baarsik_lakshya_vaar')->nullable();
            $table->float('baarsik_lakshya_budget')->nullable();
            $table->float('pahilo_traimasik_lakshya_pariman')->nullable();
            $table->float('pahilo_traimasik_lakshya_vaar')->nullable();
            $table->float('pahilo_traimasik_lakshya_budget')->nullable();
            $table->float('dosro_traimasik_lakshya_pariman')->nullable();
            $table->float('dosro_traimasik_lakshya_vaar')->nullable();
            $table->float('dosro_traimasik_lakshya_budget')->nullable();
            $table->float('tesro_traimasik_lakshya_pariman')->nullable();
            $table->float('tesro_traimasik_lakshya_vaar')->nullable();
            $table->float('tesro_traimasik_lakshya_budget')->nullable();
            $table->float('chautho_traimasik_lakshya_pariman')->nullable();
            $table->float('chautho_traimasik_lakshya_vaar')->nullable();
            $table->float('chautho_traimasik_lakshya_budget')->nullable();
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
        Schema::connection('pcwprs_data')->dropIfExists('kriyakalap_lakshya');
    }
}
