<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
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
        $data = FamilyMember::create($request->all());
        return response(['data' => $data], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyMember $familyMember)
    {
        $data = collect($familyMember);
        return response(['data' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyMember $familyMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilyMember $familyMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FamilyMember  $familyMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(FamilyMember $familyMember)
    {
        //
    }
}
