<?php
namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Add this method in your FlightController

    public function create()
    {
        return view('flights.create');
    }

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

        // Return a view instead of JSON
        return view('flights.index', compact('flights'));
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

        // Redirect to a page with a success message
        return redirect()->route('flights.index')->with('success', 'Flight created successfully');
    }

    public function edit(string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return redirect()->route('flights.index')->with('error', 'Flight not found');
        }

        return view('flights.edit', compact('flight'));
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
            return redirect()->route('flights.index')->with('error', 'Flight not found');
        }

        // Return a view instead of JSON
        return view('flights.show', compact('flight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return redirect()->route('flights.index')->with('error', 'Flight not found');
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

        return redirect()->route('flights.index')->with('success', 'Flight updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return redirect()->route('flights.index')->with('error', 'Flight not found');
        }

        $flight->delete();

        return redirect()->route('flights.index')->with('success', 'Flight deleted successfully');
    }

    public function search(Request $request)
    {
        $query = Flight::query();
        
        // Filter by 'from' (exact match)
        if ($request->input('from') != null) {
            $query->where('from', $request->input('from'));
        }

        // Filter by 'destination' (exact match)
        if ($request->input('destination') != null) {
            $query->where('destination', $request->input('destination'));
        }

        if ($request->input('departure') != null) {
            // Use whereDate to compare only the date part
            $query->whereDate('departure', $request->input('departure'));
        }

        $flights = $query->get();

        // Return a view instead of JSON
        return view('flights.search', compact('flights'));
    }
    public function getPrice($flight_id)
{
    $flight = Flight::find($flight_id);
    if ($flight_id) {
        return response()->json(['flight_id' => $flight->price]);
    }
    return response()->json(['error' => 'Flight not found'], 404);
}
    
}
