<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Payment History Migration
 * @author Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 */

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
            $table->increments('id');
            $table->string('returnCode');
            $table->mediumText('bankURL');
            $table->integer('trazabilityCode');
            $table->integer('transactionCycle');
            $table->integer('transactionID');
            $table->string('sessionID',255);
            $table->char('bankCurrency',5);
            $table->integer('bankFactor');
            $table->integer('responseCode');
            $table->timestamps();
        });
        
        Schema::table('payments', function($table) {
            $table->index('trazabilityCode');
            $table->index('transactionID');
            $table->index('sessionID');
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
