<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Matches;
use Illuminate\Http\JsonResponse;

class MatchesController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Matches"},
     *      path="/api/v1/matches",
     *      summary="Get All Matches",
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
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Matches retrieved successfully"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array", 
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="id", 
     *                          type="integer", 
     *                          example="2"
     *                      ),
     *                      @OA\Property(
     *                          property="name", 
     *                          type="string", 
     *                          example="Bekasi FC"
     *                      ),
     *                      @OA\Property(
     *                          property="logo", 
     *                          type="string", 
     *                          example="bekasi.png"
     *                      ),
     *                      @OA\Property(
     *                          property="founded_year", 
     *                          type="integer", 
     *                          example="2004"
     *                      ),
     *                      @OA\Property(
     *                          property="address", 
     *                          type="string", 
     *                          example="Bekasi"
     *                      ),
     *                      @OA\Property(
     *                          property="city", 
     *                          type="string", 
     *                          example="Tambun"
     *                      ),
     *                  )
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
    public function index(): JsonResponse
    {
        $matches = Matches::with(['homeTeam', 'awayTeam'])->get();

        return response()->json([
            'statuscode' => 200,
            'message' => 'Matches retrieved successfully',
            'data' => $matches,
        ]);
    }

    /**
     * @OA\Post(
     *      tags={"Matches"},
     *      path="/api/v1/matches",
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
        try {
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
                'statuscode' => 201,
                'message' => 'Match created successfully',
                'match' => $match,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $th) {

            return response()->json([
                "statuscode" => 422,
                "message" => $th->validator->errors()->first()  
            ], 422);
        }        
    }

    /**
     * @OA\Get(
     *      tags={"Matches"},
     *      path="/api/v1/matches/{id}",
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
     *               @OA\Property(
     *                  property="statuscode", 
     *                  type="integer", 
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Matches found"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object", 
     *                  @OA\Property(
     *                      property="match_date", 
     *                      type="string",
     *                      format="date", 
     *                      example="2024-12-01"
     *                  ),
     *                  @OA\Property(
     *                      property="match_time", 
     *                      type="string",
     *                      format="time", 
     *                      example="18:00:00"
     *                  ),
     *                  @OA\Property(
     *                      property="home_team_id", 
     *                      type="integer", 
     *                      example="2"
     *                  ),
     *                  @OA\Property(
     *                      property="away_team_id", 
     *                      type="integer", 
     *                      example="4"
     *                  ),
     *                  @OA\Property(
     *                      property="home_team_score", 
     *                      type="integer", 
     *                      example="2"
     *                  ),
     *                  @OA\Property(
     *                      property="away_team_score", 
     *                      type="integer", 
     *                      example="4"
     *                  ),
     *              ),
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
     *      path="/api/v1/matches/{id}",
     *      summary="Update matches",
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
            return response()->json([
                'statuscode' => 404,
                'message' => 'Match not found'
            ], 404);
        }

        try {
            $request->validate([
                'match_date' => 'sometimes|required|date',
                'match_time' => 'sometimes|required',
                'home_team_id' => 'sometimes|required|exists:teams,id',
                'away_team_id' => 'sometimes|required|exists:teams,id',
                'home_score' => 'sometimes|nullable|integer',
                'away_score' => 'sometimes|nullable|integer',
            ]);

            $match->update($request->all());

            return response()->json([
                'statuscode' => 200,
                'message' => 'Match updated successfully', 'match' => $match
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $th) {

            return response()->json([
                "statuscode" => 422,
                "message" => $th->validator->errors()->first()  
            ], 422);
        }    

    }

    /**
     * @OA\Delete(
     *      tags={"Matches"},
     *      path="/api/v1/matches/{id}",
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
            return response()->json([
                'statuscode' => 404,    
                'message' => 'Match not found'
            ], 404);
        }
    
        $match->delete();
    
        return response()->json([
            'statuscode' => 200,
            'message' => 'Match deleted successfully'
        ], 200);
    }

    /**
     * @OA\Get(
     *      tags={"Matches"},
     *      path="/api/v1/matches/report",
     *      summary="Get All matches as Report",
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
     *              @OA\Property(
     *                  property="message", 
     *                  type="string", 
     *                  example="Data found"
     *              ),
     *              @OA\Property(
     *                  property="data", 
     *                  type="object", 
     *                  
     *              ),
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
     *                  example="Data not Found"
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

        if(count($matches)>0) {
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

            return response()->json([
                "statuscode" => 200,
                "message" => "Data found",
                "data" => $report
            ], 200);
        }
        else {
            return response()->json([
                "statuscode" => 404,
                "message" => "Data Not found",
            ], 404);
        } 
    }

}
