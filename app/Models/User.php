<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Family;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function registeredFamily() {
        return $this->hasOne('App\Models\Family');
    }

    public function familyMember() {
        return $this->hasOne('App\Models\FamilyMember');
    }

    public function bankAccounts() {
        return $this->hasMany('App\Models\BankAccount');
    }

    public function familyRegistrations() {
        return $this->hasMany('App\Models\FamilyRegistration');
    }

    public function registeredClients() {
        return $this->hasMany('App\Models\Client');
    }

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    public function postLoginData() {
        $family = Family::where('ref_user_id', $this->id);

        if ($family->exists()) {
            $data = [
                'id' => $this->id,
                'email' => $this->email,
                'api_token' => $this->api_token,
                'keluarga' => [
                    'id' => $family->first()->id,
                    'status' => $family->first()->status
                ]
            ];
        } else {
            $data = [
                'id' => $this->id,
                'email' => $this->email,
                'api_token' => $this->api_token,
            ];
        }

        return $data;
    }
}
