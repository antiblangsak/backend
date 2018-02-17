<?php
/**
 * Created by PhpStorm.
 * User: Syukri
 * Date: 2/11/18
 * Time: 1:30 PM
 */

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\Family;
use App\Models\Payment;
use App\Models\Claim;
use App\Models\Constants;

class DPGKController {
    public function getFamilyMembers($familyId, $registered) {
        $family = Family::find($familyId);
        $familyMembers = $family->familyMembers;

        $data = collect([]);
        foreach ($familyMembers as $familyMember) {
            $client = $familyMember->registeredAsClients->where('service_id', Constants::DPGK_SERVICE_ID);
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
        return response(['data' => [
            'registered' => $registeredMembers,
            'unregistered' => $unregisteredMembers
        ]], 200);
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

        $payments = Payment::where('service_id', Constants::DPGK_SERVICE_ID)->get()
            ->whereIn('id', $paymentIds);

        $claims = Claim::where('service_id', Constants::DPGK_SERVICE_ID)->get()
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

    public function getPaymentInfo($familyId) {
        $family = Family::find($familyId);
        $clientData = $family->familyMembersAsClients->where('service_id', Constants::DKK_SERVICE_ID);

        $familyMemberData = collect([]);
        foreach ($clientData as $client) {
            $familyMemberData->push([
                'client_id' => $client->id,
                'fullname' => $client->familyMember->fullname
            ]);
        }
        $user = $family->referencedUser;
        $bankAccounts = $user->bankAccounts;
        $bankAccountsData = collect([]);

        foreach ($bankAccounts as $bankAccount) {
            $bankAccountsData->push([
                'id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'account_number' => $bankAccount->account_number,
                'account_name' => $bankAccount->account_name
            ]);
        }
        return response(['data' => [
            'family_members' => $familyMemberData,
            'bank_accounts' => $bankAccountsData
        ]], 200);
    }

    public function getClaimInfo($familyId) {
        $family = Family::find($familyId);
        $familyData = $family->familyMembers;

        $data = collect([]);
        foreach ($familyData as $familyMember) {
            $client = $familyMember->registeredAsClients->where('service_id', Constants::DPGK_SERVICE_ID)->first();

            if (!$client) {
                continue;
            }

            $acceptedClaims = Claim::where('client_id', $client->id)
                ->where('status', Claim::STATUS_ACCEPTED)->get();
            $acceptedClaimAmount = 0;

            foreach ($acceptedClaims as $claim) {
                $acceptedClaimAmount = $acceptedClaimAmount + $claim->claim_amount;
            }
            $data->push([
                'client_id' => $client->id,
                'name' => $familyMember->fullname,
                'remaining_amount' => Claim::MAX_CLAIM_AMOUNT - $acceptedClaimAmount
            ]);
        }
        return response(['data' => $data], 200);
    }
}