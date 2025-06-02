<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flights = Flight::select(
            'flight_id',
            'airline_name',
            'flight_number',
            'departure',
            'arrival',
            'destination',
            'from',
            'price',
            'status'
        )->get();

        return response()->json([
            'status' => 'success',
            'data' => $flights
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'airline_name' => 'required|string|max:255',
            'flight_number' => 'required|string|max:100',
            'departure' => 'required|date',
            'arrival' => 'required|date|after:departure',
            'destination' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string|max:100',
        ]);

        $flight = Flight::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Flight created successfully',
            'data' => [
                'flight_id' => $flight->flight_id,
                'airline_name' => $flight->airline_name,
                'flight_number' => $flight->flight_number,
                'departure' => $flight->departure,
                'arrival' => $flight->arrival,
                'destination' => $flight->destination,
                'from' => $flight->from,
                'price' => $flight->price,
                'status' => $flight->status,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flight = Flight::select(
            'flight_id',
            'airline_name',
            'flight_number',
            'departure',
            'arrival',
            'destination',
            'from',
            'price',
            'status'
        )->find($id);

        if (!$flight) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flight not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $flight
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flight not found'
            ], 404);
        }

        $validated = $request->validate([
            'airline_name' => 'sometimes|required|string|max:255',
            'flight_number' => 'sometimes|required|string|max:100',
            'departure' => 'sometimes|required|date',
            'arrival' => 'sometimes|required|date|after:departure',
            'destination' => 'sometimes|required|string|max:255',
            'from' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string|max:100',
        ]);

        $flight->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Flight updated successfully',
            'data' => [
                'flight_id' => $flight->flight_id,
                'airline_name' => $flight->airline_name,
                'flight_number' => $flight->flight_number,
                'departure' => $flight->departure,
                'arrival' => $flight->arrival,
                'destination' => $flight->destination,
                'from' => $flight->from,
                'price' => $flight->price,
                'status' => $flight->status,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flight not found'
            ], 404);
        }

        $flight->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Flight deleted successfully'
        ]);
    }

    public function search(Request $request)
    {
        $query = Flight::query();

        if ($request->has('airline_name')) {
            $query->where('airline_name', 'like', '%' . $request->input('airline_name') . '%');
        }

        if ($request->has('departure')) {
            $query->whereDate('departure', $request->input('departure'));
        }

        if ($request->has('arrival')) {
            $query->whereDate('arrival', $request->input('arrival'));
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->input('destination') . '%');
        }

        if ($request->has('from')) {
            $query->where('from', 'like', '%' . $request->input('from') . '%');
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $flights = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $flights
        ]);
    }
}
