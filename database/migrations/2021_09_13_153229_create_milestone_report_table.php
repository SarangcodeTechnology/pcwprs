<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilestoneReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('milestone_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('milestone_pragati_id');
            $table->mediumText("prarambhik_karya_suru_milestone")->nullable();
            $table->mediumText("prarambhik_karya_suru_samayavadhi")->nullable();
            $table->mediumText("prarambhik_karya_jari_milestone")->nullable();
            $table->mediumText("prarambhik_karya_jari_samayavadhi")->nullable();
            $table->mediumText("prarambhik_karya_suru_reason")->nullable();
            $table->mediumText("prarambhik_karya_jari_reason")->nullable();
            $table->mediumText("prarambhik_karya_sampanna_milestone")->nullable();
            $table->mediumText("prarambhik_karya_sampanna_samayavadhi")->nullable();
            $table->mediumText("prarambhik_karya_sampanna_reason")->nullable();
            $table->mediumText("karyakram_karyanayan_suru_milestone")->nullable();
            $table->mediumText("karyakram_karyanayan_suru_samayavadhi")->nullable();
            $table->mediumText("karyakram_karyanayan_suru_reason")->nullable();
            $table->mediumText("karyakram_karyanayan_jari_reason")->nullable();
            $table->mediumText("karyakram_karyanayan_jari_samayavadhi")->nullable();
            $table->mediumText("karyakram_karyanayan_jari_milestone")->nullable();
            $table->mediumText("karyakram_karyanayan_sampanna_milestone")->nullable();
            $table->mediumText("karyakram_karyanayan_sampanna_samayavadhi")->nullable();
            $table->mediumText("karyakram_karyanayan_sampanna_reason")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->dropIfExists('milestone_reports');
    }
}
