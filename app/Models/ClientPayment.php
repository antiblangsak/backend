<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_id', 'client_id'
    ];

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    public function payment() {
        return $this->belongsTo('App\Models\Payment');
    }
}
