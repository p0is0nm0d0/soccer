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
     * @OA\Post(
     *      tags={"Players"},
     *      path="/api/players",
     *      summary="Store a new players",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(
     *                 property="name", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Bekasi FC"
     *              ),
     *              @OA\Property(
     *                 property="height", 
     *                 type="integer", 
     *                 example="175"
     *              ),
     *              @OA\Property(
     *                 property="weight", 
     *                 type="integer", 
     *                 example="67"
     *              ),
     *              @OA\Property(
     *                 property="position", 
     *                 type="string", 
     *                 format="text", 
     *                 example="penyerang"
     *              ),
     *              @OA\Property(
     *                 property="jersey_number", 
     *                 type="integer", 
     *                 example="1"
     *              ),
     *              @OA\Property(
     *                 property="team_id", 
     *                 type="integer", 
     *                 example="1"
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
     *                  example="Player created successfully"
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
     *                  example="The name field is required."
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
            'statuscode' => 201,
            'message' => 'Player created successfully',
        ], 201);
    }
    

    /**
     * @OA\Get(
     *      tags={"Players"},
     *      path="/api/players/{id}",
     *      summary="Get players detail by id",
     *      @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Player id",
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
    public function show($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player, 200);
    }


    /**
     * @OA\Put(
     *      tags={"Players"},
     *      path="/api/players/{id}",
     *      summary="Store a new players",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Player id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ), 
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(
     *                 property="name", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Bekasi FC"
     *              ),
     *              @OA\Property(
     *                 property="height", 
     *                 type="integer", 
     *                 example="175"
     *              ),
     *              @OA\Property(
     *                 property="weight", 
     *                 type="integer", 
     *                 example="67"
     *              ),
     *              @OA\Property(
     *                 property="position", 
     *                 type="string", 
     *                 format="text", 
     *                 example="penyerang"
     *              ),
     *              @OA\Property(
     *                 property="jersey_number", 
     *                 type="integer", 
     *                 example="1"
     *              ),
     *              @OA\Property(
     *                 property="team_id", 
     *                 type="integer", 
     *                 example="1"
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
     *                  example="Player created successfully"
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
     *                  example="The name field is required."
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
     * @OA\Delete(
     *      tags={"Players"},
     *      path="/api/players/{id}",
     *      summary="Store a new team",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Team id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *                  example="Player has been deleted successfully"
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
     *                  example="Player not found."
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
