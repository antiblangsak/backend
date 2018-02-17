<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'claim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id', 'client_id', 'ref_user_id', 'claim_number', 'claim_amount', 'status', 'note'
    ];

    const STATUS_WAITING_VERIFICATION = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    const MAX_CLAIM_AMOUNT = 125000000;

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    public function referencedUser() {
        return $this->belongsTo('App\Models\User', 'ref_user_id', 'id');
    }

    public static function generateClaimNumber($serviceId, $createdAt, $id) {
        $createdAt = strtotime($createdAt);
        return 'CLM-' . Constants::getServiceCode($serviceId) . '-' . date('Ymd', $createdAt) . '-' . sprintf('%05d', $id);
    }

    public function claimRequest() {
        return $this->hasOne('App\Models\ClaimRequest');
    }
}
