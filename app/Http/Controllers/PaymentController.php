<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ClientPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment = Payment::create($request->all());
        $payment->payment_number = Payment::generatePaymentNumber($payment->service_id, $payment->created_at, $payment->id);
        $payment->save();

        $clients = collect([]);
        foreach ($request->clients as $client_id) {
            ClientPayment::create(['client_id' => $client_id, 'payment_id' => $payment->id]);
            $clients->push($client_id);
        }
        return response(['data' => $payment, 'clients' => $clients], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $clients = $payment->clientPayments;
        $bankAcc = $payment->bankAccount;
        $refUser = $bankAcc->referencedUser;

        $client_data = collect([]);
        foreach ($clients as $paymentClient) {
            $familyMember = $paymentClient->client->familyMember;
            $client_data->push(
                $familyMember->fullname
            );
        }
        $data = collect([
            'payment_number' => $payment->payment_number,
            'payment_amount' => $payment->payment_amount,
            'clients' => $client_data,
            'bank_account' => [
                'bank_name' => $bankAcc->bank_name,
                'account_number' => $bankAcc->account_number,
                'ref_user' => $refUser->name
            ],
            'status' => $payment->status
        ]);
        return response(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return response(['data' => $payment], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
