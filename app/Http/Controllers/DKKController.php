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
            $client = $familyMember->registeredAsClients->where('service_id', Constants::DKK_SERVICE_ID);
            if ($registered) {
                if ($client->count() > 0) {
                    $client = $client->first();
                    $data->push([
                        'id' => $familyMember->id,
                        'fullname' => $familyMember->fullname,
                        'relation' => $familyMember->relation,
                        'status' => $client->status
                    ]);
                }
            } else {
                if ($client->count() == 0) {
                    $data->push([
                        'id' => $familyMember->id,
                        'fullname' => $familyMember->fullname,
                        'relation' => $familyMember->relation
                    ]);
                }
            }
        }

        return $data;
    }

    public function getUnregisteredFamilyMembers($familyId) {
        $data = $this->getFamilyMembers($familyId, false);
        return response(['data' => $data], 200);
    }

    public function getRegisteredFamilyMembers($familyId) {
        $data = $this->getFamilyMembers($familyId, true);
        return response(['data' => $data], 200);
    }

    public function getSplittedFamilyMembers($familyId) {
        $registeredMembers = $this->getFamilyMembers($familyId, true);
        $unregisteredMembers = $this->getFamilyMembers($familyId, false);
        return collect([
            'registered' => $registeredMembers,
            'unregistered' => $unregisteredMembers
        ]);
    }

    public function getHistory($familyId) {
        $family = Family::find($familyId);
        $clients = $family->familyMembersAsClients;

        $clientIds = [];
        foreach ($clients as $client) {
            array_push($clientIds, $client->id);
        }

        $clientPayments = ClientPayment::all()->whereIn('client_id', $clientIds);

        $paymentIds = [];
        foreach ($clientPayments as $clientPayment) {
            array_push($paymentIds, $clientPayment->payment_id);
        }

        $payments = Payment::where('service_id', Constants::DKK_SERVICE_ID)->get()
            ->whereIn('id', $paymentIds);

        $claims = Claim::where('service_id', Constants::DKK_SERVICE_ID)->get()
            ->whereIn('client_id', $clientIds);

        $data = collect([]);

        foreach ($payments as $payment) {
            $data->push([
                'type' => 'Pembayaran',
                'id' => $payment->id,
                'status' => $payment->status,
                'created_at' => $payment->created_at->toDateTimeString()
            ]);
        }

        foreach ($claims as $claim) {
            $data->push([
                'type' => 'Klaim',
                'id' => $claim->id,
                'status' => $claim->status,
                'created_at' => $claim->created_at->toDateTimeString()
            ]);
        }

        $data = $data->sortByDesc('created_at')->values()->all();
        return response(['data' => $data], 200);
    }
}
