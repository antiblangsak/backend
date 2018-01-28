<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use Illuminate\Http\Request;
use App\Models\Family;
use App\Models\DWK;
use App\Models\Payment;
use App\Models\Claim;
use App\Models\Constants;

class DKKController extends Controller
{
    public function getFamilyMembers($familyId, $registered) {
        $family = Family::find($familyId);
        $familyMembers = $family->familyMembers;

        $data = collect([]);
        foreach ($familyMembers as $familyMember) {
            if ($registered) {
                if ($familyMember->registeredAsClients->where('service_id', Constants::DKK_SERVICE_ID)->count() > 0) {
                    $data->push([
                        'id' => $familyMember->id,
                        'fullname' => $familyMember->fullname,
                        'relation' => $familyMember->relation
                    ]);
                }
            } else {
                if ($familyMember->registeredAsClients->where('service_id', Constants::DKK_SERVICE_ID)->count() == 0) {
                    $data->push([
                        'id' => $familyMember->id,
                        'fullname' => $familyMember->fullname,
                        'relation' => $familyMember->relation
                    ]);
                }
            }
        }

        return response(['data' => $data], 200);
    }

    public function getUnregisteredFamilyMembers($familyId) {
        return $this->getFamilyMembers($familyId, false);
    }

    public function getRegisteredFamilyMembers($familyId) {
        return $this->getFamilyMembers($familyId, true);
    }
}
