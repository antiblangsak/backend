<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'claim_request';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'claim_id', 'status', 'file_name'
    ];

    public function claimInfo() {
        return $this->belongsTo('App\Models\Claim');
    }
}
