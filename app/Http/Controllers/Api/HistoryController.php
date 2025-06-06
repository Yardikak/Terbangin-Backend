<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = History::with(['user', 'ticket', 'payment'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $histories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ticket_id' => 'required|exists:tickets,ticket_id',
            'payment_id' => 'required|exists:payments,payment_id',
            'flight_date' => 'required|date'
        ]);

        $history = History::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'History created successfully',
            'data' => $history->load(['user', 'ticket', 'payment'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $history = History::with(['user', 'ticket', 'payment'])->find($id);

        if (!$history) {
            return response()->json([
                'status' => 'error',
                'message' => 'History not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $history
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $history = History::find($id);

        if (!$history) {
            return response()->json([
                'status' => 'error',
                'message' => 'History not found'
            ], 404);
        }

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'ticket_id' => 'sometimes|required|exists:tickets,ticket_id',
            'payment_id' => 'sometimes|required|exists:payments,payment_id',
            'flight_date' => 'sometimes|required|date'
        ]);

        $history->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'History updated successfully',
            'data' => $history->load(['user', 'ticket', 'payment'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $history = History::find($id);

        if (!$history) {
            return response()->json([
                'status' => 'error',
                'message' => 'History not found'
            ], 404);
        }

        $history->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'History deleted successfully'
        ]);
    }
}
