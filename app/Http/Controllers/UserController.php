<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $properties = [];

        if(Auth::user()->role == 'admin') {
            $properties = User::paginate($request->per_page ?? 6);
        }

        return response()->json($properties);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if(User::where('email', $request->email)->first()) {
            return response()->json(['error' => 'Emails should be unique'], 400);
        }

        if($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
        }

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if($user) {
            $user->delete();
        }

        return response()->json(['status' => 'success']);
    }
}
