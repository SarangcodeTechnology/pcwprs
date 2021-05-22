<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAayojanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('aayojana', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('aarthik_barsa_id')->nullable();
            $table->string('budget_no')->nullable();
            $table->string('name')->nullable();
            $table->string('mantralaya_name')->nullable();
            $table->string('bivag_sanstha_name')->nullable();
            $table->string('sthan_jilla')->nullable();
            $table->string('aayojana_start_date')->nullable();
            $table->string('aayojana_end_date')->nullable();
            $table->string('aayojana_karyalaya_pramukh_name')->nullable();
            $table->string('baarsik_budget')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->dropIfExists('aayojana');
    }
}
