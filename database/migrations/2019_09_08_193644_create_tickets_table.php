<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigInteger('ticketId')->primary();
            $table->string('type', 20)->nullable();
            $table->bigInteger('assignedTo')->nullable();
            $table->time('startTime')->nullable();
            $table->string('touchPoint', 50)->nullable();
            $table->string('issue', 1000)->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('service_id')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
