<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_account';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'bank_name', 'branch_name', 'account_number', 'account_name', 'account_photo'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
