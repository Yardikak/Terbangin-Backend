<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FlightController extends Controller
{
    /**
     * Menampilkan daftar penerbangan.
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

        return view('flights.index', ['flights' => $flights]);
    }

    /**
     * Menampilkan form untuk membuat penerbangan baru.
     */
    public function create()
    {
        return view('flights.create');
    }

    /**
     * Menyimpan penerbangan yang baru dibuat.
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

        return redirect()->route('flights.index')->with('status', 'Penerbangan berhasil dibuat');
    }

    /**
     * Menampilkan detail penerbangan tertentu.
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
            return redirect()->route('flights.index')->with('error', 'Penerbangan tidak ditemukan');
        }

        return view('flights.show', ['flight' => $flight]);
    }

    /**
     * Menampilkan form untuk mengedit penerbangan yang ada.
     */
    public function edit(string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return redirect()->route('flights.index')->with('error', 'Penerbangan tidak ditemukan');
        }

        return view('flights.edit', ['flight' => $flight]);
    }

    /**
     * Memperbarui penerbangan yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        $flight = Flight::find($id);

        if (!$flight) {
            return redirect()->route('flights.index')->with('error', 'Penerbangan tidak ditemukan');
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

        return redirect()->route('flights.index')->with('status', 'Penerbangan berhasil diperbarui');
    }

    /**
     * Menghapus penerbangan yang ada.
     */
    public function destroy(string $flight_id)
{
    $flight = Flight::find($flight_id);

    if (!$flight) {
        return redirect()->route('flights.index')->with('error', 'Penerbangan tidak ditemukan');
    }

    $flight->delete();

    return redirect()->route('flights.index')->with('status', 'Penerbangan berhasil dihapus');
}


    /**
     * Mencari penerbangan berdasarkan kriteria.
     */
    public function search(Request $request)
    {
        $query = Flight::query();

        // Filter berdasarkan 'from' (pencocokan tepat)
        if ($request->input('from') != null) {
            $query->where('from', $request->input('from'));
        }

        // Filter berdasarkan 'destination' (pencocokan tepat)
        if ($request->input('destination') != null) {
            $query->where('destination', $request->input('destination'));
        }

        // Filter berdasarkan 'departure' (pencocokan tanggal)
        if ($request->input('departure') != null) {
            $query->whereDate('departure', $request->input('departure'));
        }

        $flights = $query->get();

        return view('flights.index', ['flights' => $flights]);
    }
}
