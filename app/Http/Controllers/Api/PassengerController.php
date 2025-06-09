<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Passenger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PassengerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $passengers = Passenger::select(
            'passenger_id',
            'name',
            'title',
            'nik_number',
            'birth_date'
        )->get();

        return response()->json([
            'status' => 'success',
            'data' => $passengers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|in:Mr,Mrs,Ms',
            'nik_number' => 'required|string|max:255|unique:passengers,nik_number',
            'birth_date' => 'required|date|before:today',
        ]);

        $passenger = Passenger::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Passenger created successfully',
            'data' => [
                'passenger_id' => $passenger->passenger_id,
                'name' => $passenger->name,
                'title' => $passenger->title,
                'nik_number' => $passenger->nik_number,
                'birth_date' => $passenger->birth_date,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $passenger = Passenger::select(
            'passenger_id',
            'name',
            'title',
            'nik_number',
            'birth_date'
        )->find($id);

        if (!$passenger) {
            return response()->json([
                'status' => 'error',
                'message' => 'Passenger not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $passenger
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            return response()->json([
                'status' => 'error',
                'message' => 'Passenger not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|in:Mr,Mrs,Ms',
            'nik_number' => 'sometimes|required|string|max:255|unique:passengers,nik_number,' . $id . ',passenger_id',
            'birth_date' => 'sometimes|required|date|before:today',
        ]);

        $passenger->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Passenger updated successfully',
            'data' => [
                'passenger_id' => $passenger->passenger_id,
                'user_id' => $passenger->user_id,
                'name' => $passenger->name,
                'title' => $passenger->title,
                'nik_number' => $passenger->nik_number,
                'birth_date' => $passenger->birth_date,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $passenger = Passenger::find($id);

        if (!$passenger) {
            return response()->json([
                'status' => 'error',
                'message' => 'Passenger not found'
            ], 404);
        }

        $passenger->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Passenger deleted successfully'
        ]);
    }

}