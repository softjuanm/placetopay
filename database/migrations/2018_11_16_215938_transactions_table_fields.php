<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 * Transaction Migration
 * Add two missing columns to table
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.2
 *
 */
class TransactionsTableFields extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::table('transactions', function($table) {
            $table->string('responseReasonCode')->nullable()->after('responseCode');
        });
        Schema::table('transactions', function($table) {
            $table->string('responseReasonText')->nullable()->after('responseReasonCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
