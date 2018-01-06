<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect([]);
        $allBankAccounts = BankAccount::all();

        foreach ($allBankAccounts as $bankAcc) {
            $user = $bankAcc->user;
            $data->push([
                'id' => $bankAcc->id,
                'user_id' => $user->id,
                'bank_name' => $bankAcc->bank_name,
                'branch_name' => $bankAcc->branch_name,
                'account_number' => $bankAcc->account_number,
                'account_name' => $bankAcc->account_name,
                'account_photo' => $bankAcc->account_photo,
                'created_at' => $bankAcc->created_at->toDateTimeString(),
                'updated_at' => $bankAcc->updated_at->toDateTimeString()
            ]);
        }
        return response(['data' => $data], 200);
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
        $bankAccount = BankAccount::create($request->all());
        return response(['data' => $bankAccount], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(BankAccount $bankAccount)
    {
        $data = collect($bankAccount);
        return response(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankAccount $bankAccount)
    {
        //
    }
}
