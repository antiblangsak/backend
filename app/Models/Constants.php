<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    const DPGK_SERVICE_ID = 1;
    const DKK_SERVICE_ID = 2;
    const DWK_SERVICE_ID = 3;

    public static function getServiceCode($serviceId) {
        switch ($serviceId) {
            case 1: return 'DPGK';
            case 2: return 'DKK';
            case 3: return 'DWK';
            default: return 'Others';
        }
    }
}
