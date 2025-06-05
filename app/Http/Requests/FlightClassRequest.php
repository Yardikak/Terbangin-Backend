<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightClassRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $flightId = $this->input('flight_id');
        $currentUsedSeats = \App\Models\FlightClass::where('flight_id', $flightId)->sum('seat_capacity');
        $totalCapacity = \App\Models\Flight::find($flightId)->total_seats;

        return [
            'flight_id' => 'required|exists:flights,id',
            'class' => [
                'required',
                Rule::in(['economy', 'business', 'first']),
                Rule::unique('flight_classes')->where(function ($query) use ($flightId) {
                    return $query->where('flight_id', $flightId);
                })
            ],
            'seat_capacity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($currentUsedSeats, $totalCapacity) {
                    if (($currentUsedSeats + $value) > $totalCapacity) {
                        $remaining = $totalCapacity - $currentUsedSeats;
                        $fail("Total kursi melebihi kapasitas pesawat. Sisa kuota: {$remaining} kursi");
                    }
                }
            ],
            'price' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'class.unique' => 'Kelas ini sudah ada untuk penerbangan terpilih',
            'seat_capacity.min' => 'Kapasitas kursi minimal 1'
        ];
    }
}
