<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'family_member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'family_id', 'fullname', 'nik', 'gender', 'birth_place', 'birth_date', 'religion', 'education', 'occupation',
        'marital_status', 'relation', 'nationality', 'passport_license', 'residential_license', 'father_name',
        'mother_name'
    ];

    public function family() {
        return $this->belongsTo('App\Models\Family');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
