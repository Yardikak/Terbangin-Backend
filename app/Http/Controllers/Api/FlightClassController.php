<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightClassRequest;
use App\Models\Flight;
use App\Models\FlightClass;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FlightClassController extends Controller
{
    public function store(FlightClassRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $flight = Flight::findOrFail($request->flight_id);
            $usedSeats = FlightClass::where('flight_id', $flight->id)->sum('seat_capacity');
            $remainingSeats = $flight->total_seats - $usedSeats;

            if ($request->seat_capacity > $remainingSeats) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total seats exceed aircraft capacity',
                    'remaining_seats' => $remainingSeats
                ], 422);
            }

            $flightClass = FlightClass::create([
                'flight_id' => $flight->id,
                'class' => $request->class,
                'seat_capacity' => $request->seat_capacity,
                'available_seats' => $request->seat_capacity,
                'price' => $request->price
            ]);

            $flightClass->updateAvailableSeats();

            return response()->json([
                'status' => 'success',
                'data' => $flightClass,
                'remaining_seats' => $this->calculateRemainingSeats($flight->id)
            ], 201);
        });
    }

    public function update(FlightClassRequest $request, FlightClass $flightClass): JsonResponse
    {
        return DB::transaction(function () use ($request, $flightClass) {
            $flight = $flightClass->flight;
            $otherClassesSeats = FlightClass::where('flight_id', $flight->id)
                                            ->where('id', '!=', $flightClass->id)
                                            ->sum('seat_capacity');
            
            $remainingSeats = $flight->total_seats - $otherClassesSeats;

            if ($request->seat_capacity > $remainingSeats) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total seats exceed remaining aircraft capacity',
                    'remaining_seats' => $remainingSeats
                ], 422);
            }

            // Calculate available seats adjustment
            $seatDifference = $request->seat_capacity - $flightClass->seat_capacity;
            $newAvailableSeats = $flightClass->available_seats + $seatDifference;

            $flightClass->update([
                'class' => $request->class,
                'seat_capacity' => $request->seat_capacity,
                'available_seats' => max(0, $newAvailableSeats), // Prevent negative
                'price' => $request->price
            ]);

            $this->updateAvailableSeats($flight->id);

            return response()->json([
                'status' => 'success',
                'data' => $flightClass,
                'remaining_seats' => $this->calculateRemainingSeats($flight->id)
            ]);
        });
    }

    public function destroy(FlightClass $flightClass): JsonResponse
    {
        $flightClass->delete();
        
        $this->updateAvailableSeats($flightClass->flight_id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Flight class deleted successfully',
            'remaining_seats' => $this->calculateRemainingSeats($flightClass->flight_id)
        ]);
    }

    private function calculateRemainingSeats(int $flightId): int
    {
        $flight = Flight::findOrFail($flightId);
        $usedSeats = FlightClass::where('flight_id', $flightId)->sum('seat_capacity');
        
        return $flight->total_seats - $usedSeats;
    }

    private function updateAvailableSeats(int $flightId)
    {
        $flightClasses = FlightClass::where('flight_id', $flightId)->get();

        foreach ($flightClasses as $class) {
            $seatsBooked = Ticket::where('flight_class_id', $class->id)->sum('quantity');
            $class->available_seats = max(0, $class->seat_capacity - $seatsBooked);
            $class->save();
        }
    }
}
