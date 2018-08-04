<?php
/**
 * Created by PhpStorm.
 * User: Syukri
 * Date: 8/4/18
 * Time: 5:58 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token'
    ];
}
