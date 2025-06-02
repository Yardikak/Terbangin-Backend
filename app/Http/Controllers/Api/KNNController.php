<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketRecommender;
use Illuminate\Http\Request;

class KNNController extends Controller
{
    public function recommendLeastPopular(Request $request)
    {
        $k = $request->input('k', 10);
        $recommender = new TicketRecommender($k);
        
        $leastPopular = $recommender->findLeastPopularTickets();
        
        return response()->json([
            'status' => 'success',
            'data' => $leastPopular
        ]);
    }
}