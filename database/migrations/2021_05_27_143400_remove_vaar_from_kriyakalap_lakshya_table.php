<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveVaarFromKriyakalapLakshyaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->table('kriyakalap_lakshya', function (Blueprint $table) {
            $table->dropColumn('aayojana_kul_kriyakalap_vaar');
            $table->dropColumn('gata_aarthik_barsa_sammako_vaar');
            $table->dropColumn('baarsik_lakshya_vaar');
            $table->dropColumn('pahilo_traimasik_lakshya_vaar');
            $table->dropColumn('dosro_traimasik_lakshya_vaar');
            $table->dropColumn('tesro_traimasik_lakshya_vaar');
            $table->dropColumn('chautho_traimasik_lakshya_vaar');
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
            $table->float('aayojana_kul_kriyakalap_vaar')->nullable();
            $table->float('gata_aarthik_barsa_sammako_vaar')->nullable();
            $table->float('baarsik_lakshya_vaar')->nullable();
            $table->float('pahilo_traimasik_lakshya_vaar')->nullable();
            $table->float('dosro_traimasik_lakshya_vaar')->nullable();
            $table->float('tesro_traimasik_lakshya_vaar')->nullable();
            $table->float('chautho_traimasik_lakshya_vaar')->nullable();
        });
    }
}
