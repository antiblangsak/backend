<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = $this->getUserProfile($id);
        return response(['data' => $profile], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserProfile($userId) {
        $user = User::find($userId);
        $profile = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => '+6281000000000', // TODO
            'gender' => 'Laki-laki', // TODO
            'bank_accounts' => $this->getBankAccountsHelper($user->id),
            'member_since' => $user->created_at->toDateString()
        ];
        return $profile;
    }

    public function getBankAccounts($id) {
        return response(['data' => $this->getBankAccountsHelper($id)], 200);
    }

    public function getBankAccountsHelper($id) {
        $user = User::find($id);
        $bankAccounts = $user->bankAccounts;
        $data = collect([]);

        foreach ($bankAccounts as $bankAccount) {
            $data->push([
                'id' => $bankAccount->id,
                'name' => $bankAccount->account_name . ' - ' . $bankAccount->bank_name
            ]);
        }
        return $data;
    }

    public function connectUserToFamily(Request $request) {
        $user_id = $request->input('user_id', 99);
        $nik = $request->input('nik');
        $familyId = $request->input('family_id');

        try {
            $family = Family::findOrFail($familyId);
            $familyMember = $family->familyMembers()->where('nik', $nik)->firstOrFail();
            $familyMember->user_id = $user_id;
            $familyMember->save();
        } catch (ModelNotFoundException $e) {
            if ($e->getModel() == 'App\Models\Family') {
                return response([
                    'error' => 'Keluarga tidak ditemukan.',
                    'last_trace' => $e->getMessage()
                ], 404);
            } else {
                return response([
                    'error' => 'NIK Anda tidak ditemukan pada data keluarga.',
                    'last_trace' => $e->getMessage()],
                    404);
            }
        } catch (QueryException $e) {
            return response([
                'error' => 'Id user tidak valid.',
                'last_trace' => $e->getMessage()],
                404);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
        return response(['data' => 'Berhasil terhubung dengan keluarga.'], 201);
    }
}
