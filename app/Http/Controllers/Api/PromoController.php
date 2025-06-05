<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;


class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::select('promo_id', 'promo_code', 'description', 'discount', 'valid_until')->get();

        return response()->json([
            'status' => 'success',
            'data' => $promos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'promo_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'required|numeric|min:0',
            'valid_until' => 'required|date',
        ]);

        $promo = Promo::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Promo created successfully',
            'data' => [
                'promo_id' => $promo->promo_id,
                'promo_code' => $promo->promo_code,
                'description' => $promo->description,
                'discount' => $promo->discount,
                'valid_until' => $promo->valid_until,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promo = Promo::select('promo_id', 'promo_code', 'description', 'discount', 'valid_until')->find($id);

        if (!$promo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Promo not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $promo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Promo not found'
            ], 404);
        }

        $validated = $request->validate([
            'promo_code' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'sometimes|required|numeric|min:0',
            'valid_until' => 'sometimes|required|date',
        ]);

        $promo->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Promo updated successfully',
            'data' => [
                'promo_id' => $promo->promo_id,
                'promo_code' => $promo->promo_code,
                'description' => $promo->description,
                'discount' => $promo->discount,
                'valid_until' => $promo->valid_until,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Promo not found'
            ], 404);
        }

        $promo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Promo deleted successfully'
        ]);
    }
    public function search(Request $request)
    {
        $searchTerm = $request->json('promo_code');

        if (!$searchTerm) {
            return response()->json([
                'status' => 'error',
                'message' => 'Promo code is required'
            ], 400);
        }

        $promos = Promo::select('promo_id', 'promo_code', 'description', 'discount', 'valid_until')
            ->where('promo_code', '=', $searchTerm)
            ->get();

        if ($promos->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No promos found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            
            'data' => $promos
        ]);
    }

}
