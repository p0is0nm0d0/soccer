<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Matches;

class MatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *      tags={"Matches"},
     *      path="/api/matches",
     *      summary="Store a new matches",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"match_date"},
     *              @OA\Property(
     *                 property="match_date", 
     *                 type="string", 
     *                 format="date", 
     *                 example="2024-12-01"
     *              ),
     *              @OA\Property(
     *                 property="match_time", 
     *                 type="string", 
     *                 format="time", 
     *                 example="18:00:00"
     *              ),
     *              @OA\Property(
     *                 property="home_team_id", 
     *                 type="integer", 
     *                 example="1"
     *              ),
     *              @OA\Property(
     *                 property="away_team_id", 
     *                 type="integer", 
     *                 example="2"
     *              ),
     *          )
     *      ),  
     *      @OA\Response(
     *          response="201", 
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="201"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Match created successfully"
     *              ),
     *          ), 
     *      ),
     *      @OA\Response(
     *         response="422",
     *         description="one of fields must br required",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="422"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="The match date field is required."
     *              ),
     *          ), 
     *      ), 
     *      @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Unauthenticated"
     *              ),
     *          ), 
     *      ), 
     *      
     * )
     */
    public function store(Request $request)
    {
        //
        // Validasi data yang masuk
        $request->validate([
            'match_date' => 'required|date',
            'match_time' => 'required|date_format:H:i',
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id',
        ]);

        // Simpan data pertandingan
        $match = Matches::create([
            'match_date' => $request->input('match_date'),
            'match_time' => $request->input('match_time'),
            'home_team_id' => $request->input('home_team_id'),
            'away_team_id' => $request->input('away_team_id'),
        ]);

        return response()->json([
            'message' => 'Match created successfully',
            'match' => $match,
        ], 201);
    }

    /**
     * @OA\Get(
     *      tags={"Matches"},
     *      path="/api/matches/{id}",
     *      summary="Get matches detail by id",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Team id",
     *         required=true,
     *      ), 
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response="200", 
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="200"
     *              ),
     *              
     *          ), 
     *      ),
     *      @OA\Response(
     *         response="404",
     *         description="Team not Found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="404"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Team not Found"
     *              ),
     *          ), 
     *      ), 
     *      @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Unauthenticated"
     *              ),
     *          ), 
     *      ), 
     *      
     * )
     */
    public function show(string $id)
    {
        //
        $match = Matches::with(['homeTeam', 'awayTeam', 'goals.player'])->find($id);

        if (!$match) {
            return response()->json(['message' => 'Match not found'], 404);
        }

        return response()->json($match, 200);
    }

    /**
     * @OA\Put(
     *      tags={"Matches"},
     *      path="/api/matches/{id}",
     *      summary="Store a new matches",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Match id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),    
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"match_date"},
     *              @OA\Property(
     *                 property="match_date", 
     *                 type="string", 
     *                 format="date", 
     *                 example="2024-12-01"
     *              ),
     *              @OA\Property(
     *                 property="match_time", 
     *                 type="string", 
     *                 format="time", 
     *                 example="18:00:00"
     *              ),
     *              @OA\Property(
     *                 property="home_team_id", 
     *                 type="integer", 
     *                 example="1"
     *              ),
     *              @OA\Property(
     *                 property="away_team_id", 
     *                 type="integer", 
     *                 example="2"
     *              ),
     *          )
     *      ),  
     *      @OA\Response(
     *          response="201", 
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="201"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Match updated successfully"
     *              ),
     *          ), 
     *      ),
     *      @OA\Response(
     *         response="422",
     *         description="one of fields must br required",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="422"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="The match date field is required."
     *              ),
     *          ), 
     *      ), 
     *      @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Unauthenticated"
     *              ),
     *          ), 
     *      ), 
     *      
     * )
     */
    public function update(Request $request, string $id)
    {
        //
        $match = Matches::find($id);

        if (!$match) {
            return response()->json(['message' => 'Match not found'], 404);
        }

        $request->validate([
            'match_date' => 'sometimes|required|date',
            'match_time' => 'sometimes|required',
            'home_team_id' => 'sometimes|required|exists:teams,id',
            'away_team_id' => 'sometimes|required|exists:teams,id',
            'home_score' => 'sometimes|nullable|integer',
            'away_score' => 'sometimes|nullable|integer',
        ]);

        $match->update($request->all());

        return response()->json(['message' => 'Match updated successfully', 'match' => $match], 200);

    }

    /**
     * @OA\Delete(
     *      tags={"Matches"},
     *      path="/api/matches/{id}",
     *      summary="Delete matches",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Match id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),    
     *      @OA\Response(
     *          response="201", 
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="201"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Match deleted successfully"
     *              ),
     *          ), 
     *      ),
     *      @OA\Response(
     *         response="404",
     *         description="Match not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="404"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Match not found."
     *              ),
     *          ), 
     *      ), 
     *      @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Unauthenticated"
     *              ),
     *          ), 
     *      ), 
     *      
     * )
     */
    public function destroy(string $id)
    {
        $match = Matches::find($id);

        if (!$match) {
            return response()->json(['message' => 'Match not found'], 404);
        }
    
        $match->delete();
    
        return response()->json(['message' => 'Match deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *      tags={"Matches"},
     *      path="/api/matches/{id}",
     *      summary="Get matches detail by id",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Team id",
     *         required=true,
     *      ), 
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response="200", 
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="200"
     *              ),
     *              
     *          ), 
     *      ),
     *      @OA\Response(
     *         response="404",
     *         description="Team not Found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="404"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Team not Found"
     *              ),
     *          ), 
     *      ), 
     *      @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="401"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Unauthenticated"
     *              ),
     *          ), 
     *      ), 
     *      
     * )
     */

    public function report()
    {
        // Ambil semua data pertandingan yang telah selesai
        $matches = Matches::with(['homeTeam', 'awayTeam', 'goals.player'])
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->get();

        $report = [];

        foreach ($matches as $match) {
            // Hitung status akhir pertandingan
            $status = "Draw";
            if ($match->home_score > $match->away_score) {
                $status = "Tim Home Menang";
            } elseif ($match->home_score < $match->away_score) {
                $status = "Tim Away Menang";
            }

            // Hitung pencetak gol terbanyak
            $goalCounts = $match->goals->groupBy('player_id')
                ->map(fn($goals) => count($goals));
            $topScorerId = $goalCounts->sortDesc()->keys()->first();
            $topScorer = $topScorerId 
                ? Player::find($topScorerId)->name 
                : null;

            // Hitung total kemenangan masing-masing tim
            $homeWins = Matches::where('home_team_id', $match->home_team_id)
                ->whereColumn('home_score', '>', 'away_score')
                ->count();

            $awayWins = Matches::where('away_team_id', $match->away_team_id)
                ->whereColumn('away_score', '>', 'home_score')
                ->count();

            // Tambahkan data ke laporan
            $report[] = [
                'match_date' => $match->match_date,
                'match_time' => $match->match_time,
                'home_team' => $match->homeTeam->name,
                'away_team' => $match->awayTeam->name,
                'home_score' => $match->home_score,
                'away_score' => $match->away_score,
                'status' => $status,
                'top_scorer' => $topScorer,
                'home_wins_total' => $homeWins,
                'away_wins_total' => $awayWins,
            ];
        }

        return response()->json($report, 200);
    }

}
