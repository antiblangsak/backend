<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'family';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_user_id', 'kk_number', 'kk_photo', 'ktp_number', 'ktp_photo', 'family_head_name', 'address', 'rt_rw',
        'postal_code', 'village', 'subdistrict', 'city', 'province', 'status'
    ];

    public function familyMembers() {
        return $this->hasMany('App\Models\FamilyMember');
    }

    public function referencedUser() {
        return $this->belongsTo('App\Models\User');
    }
}
