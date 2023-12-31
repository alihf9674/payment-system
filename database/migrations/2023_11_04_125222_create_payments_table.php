<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
           $table->bigIncrements('id');
           $table->bigInteger('order_id');
           $table->string('methode');
           $table->string('gateway')->nullable();
           $table->string('ref_num')->nullable();
           $table->integer('amount');
           $table->tinyInteger('status')->comment('0 : Incomplete , 1 : complete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
