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
     * @OA\Post(
     *      tags={"Teams"},
     *      path="/api/teams",
     *      summary="Store a new team",
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
     *                 property="founded_year", 
     *                 type="integer", 
     *                 format="text", 
     *                 example="2000"
     *              ),
     *              @OA\Property(
     *                 property="address", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Jl hayam wuruk no 67"
     *              ),
     *              @OA\Property(
     *                 property="city", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Jakarta"
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
     *                  example="Team has been added successfully"
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
     * @OA\Get(
     *      tags={"Teams"},
     *      path="/api/teams/{id}",
     *      summary="Get teams detail by id",
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
     *                  example="Logout successful"
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
    public function show($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json([
                'statuscode' => 404,
                'message' => 'Team not found'
            ], 404);
        }

        return response()->json($team, 200);
    }


    /**
     * @OA\Put(
     *      tags={"Teams"},
     *      path="/api/teams/{id}",
     *      summary="Update a team",
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
     *                 property="founded_year", 
     *                 type="string", 
     *                 example="2000"
     *              ),
     *              @OA\Property(
     *                 property="address", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Jl hayam wuruk no 67"
     *              ),
     *              @OA\Property(
     *                 property="city", 
     *                 type="string", 
     *                 format="text", 
     *                 example="Jakarta"
     *              ),
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
     *                  example="Team has been updated successfully"
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
     * @OA\Delete(
     *      tags={"Teams"},
     *      path="/api/teams/{id}",
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
     *                  example="Team has been deleted successfully"
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
     *                  example="The Team not found."
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
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

}
