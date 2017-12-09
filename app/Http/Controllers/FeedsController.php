<?php

namespace App\Http\Controllers;

use App\Models\Feeds;
use App\Models\User;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect([]);
        $allFeeds = Feeds::all();

        foreach ($allFeeds as $feeds) {
            $user = $this->getUser($feeds->user_id);
            $data->push([
                'id' => $feeds->id,
                'user_id' => $user->id,
                'username' => $user->name,
                'content' => $feeds->content,
                'created_at' => $feeds->created_at->toDateTimeString(),
                'updated_at' => $feeds->updated_at->toDateTimeString()
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
        $feeds = Feeds::create($request->all());

        return response(['data' => $feeds], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function getUser($userId) {
        return User::find($userId);
    }
}
