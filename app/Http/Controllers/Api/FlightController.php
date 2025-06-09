<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use Illuminate\Support\Facades\Log;

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
            'status',
            'total_seats'
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
            'status' => 'required|string|max:100',
            'total_seats' => 'required|integer|min:10',
        ]);

        $flight = Flight::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Flight created successfully',
            'data' => $flight
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
            'status',
            'total_seats'
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
            'status' => 'sometimes|required|string|max:100',
            'total_seats' => 'sometimes|required|integer|min:10',
        ]);

        $flight->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Flight updated successfully',
            'data' => $flight->only([
                'flight_id',
                'airline_name',
                'flight_number',
                'departure',
                'arrival',
                'from',
                'destination',
                'status',
                'total_seats'
            ])
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
        Log::debug('SEARCH REQUEST', [
            'params' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $testQuery = Flight::whereRaw('LOWER(airline_name) LIKE ?', ['%cit%'])->count();
        Log::debug('TEST QUERY COUNT', ['count' => $testQuery]);

        $query = Flight::query();
        $appliedFilters = [];

        if ($request->filled('airline_name')) {
            $value = strtolower($request->airline_name);
            $query->whereRaw('LOWER(airline_name) LIKE ?', ["%{$value}%"]);
            $appliedFilters['airline_name'] = $value;
            Log::debug('AIRLINE FILTER APPLIED', ['value' => $value]);
        }

        if ($request->filled('destination')) {
            $value = strtolower($request->destination);
            $query->whereRaw('LOWER(destination) LIKE ?', ["%{$value}%"]);
            $appliedFilters['destination'] = $value;
        }

        if ($request->filled('from')) {
            $value = strtolower($request->from);
            $query->whereRaw('LOWER(`from`) LIKE ?', ["%{$value}%"]);
            $appliedFilters['from'] = $value;
        }

        Log::debug('FINAL QUERY', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $flights = $query->get();

        Log::debug('SEARCH RESULTS', [
            'count' => $flights->count(),
            'first' => $flights->first()
        ]);

        if ($flights->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Flight not found',
                'debug' => [
                    'applied_filters' => $appliedFilters,
                    'test_query_count' => $testQuery,
                    'suggestion' => 'Try with these sample searches:',
                    'examples' => [
                        'airline_name=cit',
                        'from=west',
                        'destination=port'
                    ]
                ]
            ], 404);
        }

    return response()->json([
            'status' => 'success',
            'data' => $flights,
            'meta' => [
                'total' => $flights->count(),
                'filters_applied' => $appliedFilters
            ]
        ]);
    }
}