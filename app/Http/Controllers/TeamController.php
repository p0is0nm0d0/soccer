<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Team;

class TeamController extends Controller
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
        $request->validate([
            'name' => 'required|string',
            'founded_year' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
        ]);
    
        $team = Team::create($request->all());
        return response()->json($team, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        return response()->json($team, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'founded_year' => 'sometimes|required|integer',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
        ]);

        $team->update($request->all());

        return response()->json(['message' => 'Team updated successfully', 'team' => $team], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

}
