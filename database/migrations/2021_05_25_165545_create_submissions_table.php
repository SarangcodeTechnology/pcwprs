<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pcwprs_data')->create('submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('aayojana_id');
            $table->unsignedBigInteger('kaaryalaya_id');
            $table->boolean('submitted')->default(0);
            $table->boolean('requested')->default(0);
            $table->boolean('editable')->default(1);
            $table->unsignedBigInteger('traimaasik_id')->nullable();
            $table->unsignedBigInteger('mahina_id')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pcwprs_data')->dropIfExists('submissions');
    }
}
