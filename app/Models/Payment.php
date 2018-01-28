<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id', 'bank_account_id', 'ref_user_id', 'payment_number', 'payment_amount', 'status', 'note'
    ];

    public function bankAccount() {
        return $this->belongsTo('App\Models\BankAccount');
    }

    public function clientPayments() {
        return $this->hasMany('App\Models\ClientPayment');
    }

    public static function generatePaymentNumber($serviceId, $createdAt, $id) {
        $createdAt = strtotime($createdAt);
        return 'PYM-' . Constants::getServiceCode($serviceId) . '-' . date('Ymd', $createdAt) . '-' . sprintf('%05d', $id);
    }
}
