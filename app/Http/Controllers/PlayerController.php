<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Player;

class PlayerController extends Controller
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
        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'height' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'position' => 'required|in:penyerang,gelandang,bertahan,penjaga gawang',
            'jersey_number' => 'required|integer|unique:players,jersey_number',
            'team_id' => 'required|exists:teams,id',
        ]);
    
        // Simpan data pemain
        $player = Player::create([
            'name' => $request->input('name'),
            'height' => $request->input('height'),
            'weight' => $request->input('weight'),
            'position' => $request->input('position'),
            'jersey_number' => $request->input('jersey_number'),
            'team_id' => $request->input('team_id'),
        ]);
    
        return response()->json([
            'message' => 'Player created successfully',
            'player' => $player,
        ], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'height' => 'sometimes|required|integer',
            'weight' => 'sometimes|required|integer',
            'position' => 'sometimes|required|in:penyerang,gelandang,bertahan,penjaga gawang',
            'jersey_number' => 'sometimes|required|integer|unique:players,jersey_number,' . $id,
        ]);

        $player->update($request->all());

        return response()->json(['message' => 'Player updated successfully', 'player' => $player], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $player->delete();

        return response()->json(['message' => 'Player deleted successfully'], 200);
    }

}
