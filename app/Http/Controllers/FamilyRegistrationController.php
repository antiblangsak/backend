<?php

namespace App\Http\Controllers;

use App\Models\FamilyRegistration;
use Illuminate\Http\Request;

class FamilyRegistrationController extends Controller
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
        $request['status'] = FamilyRegistration::STATUS_WAITING;
        $familyRegistration = FamilyRegistration::create($request->all());
        return response(['data' => [
            'id' => $familyRegistration->id,
            'status' => $familyRegistration->status
        ]], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyRegistration  $familyRegistration
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyRegistration $familyRegistration)
    {
        $data = collect($familyRegistration);
        return response(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyRegistration  $familyRegistration
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyRegistration $familyRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilyRegistration  $familyRegistration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilyRegistration $familyRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyRegistration  $familyRegistration
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyRegistration $familyRegistration)
    {
        //
    }
}
