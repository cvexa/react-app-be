<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $this->findUserByHeader($request);

        if(is_null($user) || $user->role !== 'admin') {
            $properties = Property::with('creator')
                ->when(isset($user->id), function($w) use($user) {
                    $w->where('created_by', $user->id);
                })
                ->orWhere('published', 1)
                ->paginate($request->per_page ?? 6);
        }else{
            $properties = Property::with('creator')->paginate($request->per_page ?? 6);
        }
        return response()->json($properties);
    }

    private function findUserByHeader(Request $request)
    {
        $access_token = $request->header('Authorization');
        $user = null;
        if($access_token) {
            $auth_header = explode(' ', $access_token);
            $token = $auth_header[1];
            $token_parts = explode('.', $token);
            $token_header = $token_parts[1];
            $token_header_json = base64_decode($token_header);
            $token_header_array = json_decode($token_header_json, true);
            $token_id = $token_header_array['jti'];

            $user = Token::find($token_id)->user;
        }

        return $user;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getByType(Request $request)
    {
        $type = $request->type;

        $properties = Property::with('creator')->where([
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
        $data = $request->all();
        foreach($request->all() as $field => $value) {
            if(empty($value) || $value === '') {
                unset($data[$field]);
            }
            if($value === true) {
                $data[$field] = 1;
            }
            if($value === false) {
                $data[$field] = 0;
            }
        }
        $data['created_by'] = Auth::user()?->id;
        $property = Property::create($data);
        return response()->json(['success' => true, 'property' => $property->load('creator')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Property $property)
    {
        $user = $this->findUserByHeader($request);
        if(is_null($user) && $property->published == 1) {
            return response()->json($property->load('creator'));
        }

        if($user->role == 'admin') {
            return response()->json($property->load('creator'));
        }

        if($user->role == 'user') {
            if($property->published == 1 || $property->load('creator')->creator->id == $user->id) {
                return response()->json($property->load('creator'));
            }
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
        $data = $request->all();
        $property = Property::find($id);
        foreach($request->all() as $field => $value) {
            if(empty($value) || $value === '') {
                unset($data[$field]);
            }
            if($value === true) {
                $data[$field] = 1;
            }
            if($value === false) {
                $data[$field] = 0;
            }
        }
        if(isset($data['creator'])){
            unset($data['creator']);
        }
        $property->update($data);

        return response()->json(['success' => true, 'property' => $property->load('creator')]);
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

    public function checkAvailableFeatured(Request $request)
    {
        $property = $request->property ?? null;
        $featPropertyCount = Property::where([
            ['is_featured', 1],
            ['published', 1]
        ])->count();

        $isAllowed = false;
        if($featPropertyCount < 1) {
            $isAllowed = true;
        }
        if($property && !is_null($property)) {
            $featuredProperty = Property::where([
                ['is_featured', 1],
                ['published', 1],
                ['id', $property]
            ])->first();

            if ($featuredProperty) {
                $isAllowed = true;
            }
        }

        return response()->json(['isAllowed' => $isAllowed]);
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
