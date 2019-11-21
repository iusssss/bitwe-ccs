<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScorecardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scorecard_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('scorecard_id');
            $table->string('criteria_name');
            $table->float('criteria_percentage');
            $table->string('question', 500);
            $table->string('comment', 1000)->nullable();
            $table->boolean('answer')->default('0');
            $table->integer('confidence')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scorecard_details');
    }
}
