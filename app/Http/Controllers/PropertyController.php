<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function topThree(Request $request)
    {
        $topThere = Property::where([
            ['is_top', 1],
            ['published', 1]
        ])->limit($request->limit ?? 3)->get();

        return response()->json($topThere, 200);
    }

    public function propertyTypes()
    {
        return response()->json([
           'Apartment',
           'Vila',
           'Penthouse',
           'Luxury Vila'
        ]);
    }
}
