<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_user_id', 'family_member_id', 'service_id', 'status'
    ];

    const STATUS_WAITING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_REJECTED = 2;

    const SERVICE_DPGK_ID = 1;
    const SERVICE_DKK_ID = 3;
    const SERVICE_DWK_ID = 2;

    public function getFamilyMember() {
        return $this->belongsTo('App\Models\FamilyMember');
    }

    public function referencedUser() {
        return $this->belongsTo('App\Models\User');
    }
}
