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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
