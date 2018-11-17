<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

/**
 * Transaction Migration
 * Rename Old table payments to transactions
 *
 * @author  Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 *
 */
class TransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('payments', 'transactions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
