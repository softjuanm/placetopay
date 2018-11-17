<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Transactions History Model
 * @author Juan Manuel Pinzon <softjuanm@gmail.com>
 * @version 0.1
 */
class Transactions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['returnCode', 'bankURL', 'trazabilityCode', 'transactionCycle', 'transactionID','sessionID','bankCurrency','bankFactor','responseCode'];

}
