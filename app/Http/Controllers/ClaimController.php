<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimRequest;
use Illuminate\Http\Request;

class ClaimController extends Controller
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
        $claim = Claim::create($request->all());
        $claim->claim_number = Claim::generateClaimNumber($claim->service_id, $claim->created_at, $claim->id);
        $claim->save();

        $claimRequest = new ClaimRequest();
        $claimRequest->claim_id = $claim->id;
        $claimRequest->save();
        return response(['data' => $claim], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\Response
     */
    public function show(Claim $claim)
    {
        $familyMember = $claim->client->familyMember;
        $data = collect([
            'claim_number' => $claim->claim_number,
            'client' => $familyMember->fullname,
            'claim_amount' => $claim->claim_amount,
            'note' => $claim->note,
            'status' => $claim->status
        ]);
        return response(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\Response
     */
    public function edit(Claim $claim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Claim $claim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Claim  $claim
     * @return \Illuminate\Http\Response
     */
    public function destroy(Claim $claim)
    {
        //
    }

    public function upload(Request $request) {
        $claimId = $request->claim_id;
        $base64 = $request->image;

        $claim = ClaimRequest::where('claim_id', $claimId)->firstOrFail();
        $claim->file_name = $base64;

        $data = collect([
            'claim_id' => $claim->claim_id
        ]);
        return response(['data' => $data], 201);
    }
}
