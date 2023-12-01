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
    public function index(Request $request)
    {
        $properties = Property::where('published',1)->paginate($request->per_page ?? 6);

        return response()->json($properties);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByType(Request $request)
    {
        $type = $request->type;

        $properties = Property::where([
            ['published',1],
        ])->when($type !== 'null', function($w) use($type) {
            $w->where(function($q) use ($type) {
                $q->where('type', $type);
            });
        })->paginate($request->per_page ?? 6);

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
    public function show(Property $property)
    {
        if($property->published == 1) {
            return response()->json($property);
        }

        return response()->json(['not found'],404);
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
        $property = Property::find($id);

        if($property) {
            $property->delete();
        }

        return response()->json(['status' => 'success']);
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

    public function featuredProperty(Request $request)
    {
        $featProperty = Property::where([
            ['is_featured', 1],
            ['published', 1]
        ])->first();

        return response()->json($featProperty, 200);
    }

    /**
     * @return JsonResponse
     */
    public function propertyTypes()
    {
        return response()->json([
           'Apartment',
           'Vila',
           'Penthouse',
           'Luxury Vila'
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @return JsonResponse
     */
    public function bestDealByType(Request $request, $type)
    {
        $bestDealByType = Property::where([
            ['is_best_deal', 1],
            ['published', 1],
            ['type','LIKE','%'.$type.'%']
        ])->first();

        return response()->json($bestDealByType);
    }
}
