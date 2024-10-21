<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
class LeaderBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $currentUserId = Auth::id(); // This should return the authenticated user's ID

         // Fetch leaderboard data
         $leaderboard = DB::table('users')
         ->join('scores', 'users.id', '=', 'scores.user_id')
         ->select('users.id', 'users.name', 'users.email', 'scores.score', 'scores.created_at', 'scores.updated_at')
         ->orderBy('scores.score', 'desc')
         ->get()
         ->map(function ($user, $index) {
             $user->rank = $index + 1;
             $lastAttempt = max(Carbon::parse($user->created_at), Carbon::parse($user->updated_at));
             $user->last_attempt = $lastAttempt->diffForHumans();
             return $user;
         });

     // Return JSON response for the Vue frontend
     return response()->json([
         'leaderboard' => $leaderboard,
         'currentUserId' => $currentUserId
     ]);

    }


}
