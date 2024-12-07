<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Team;
use Illuminate\Http\JsonResponse;


class TeamController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"Teams"},
     *      path="/api/v1/teams",
     *      summary="Get All teams",
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
     *                  example="Teams retrieved successfully"
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
        $teams = Team::all();
        if(count(teams)>0) {
            return response()->json([
                'statuscode' => 200,
                'message' => 'Teams retrieved successfully',
                'data' => $teams,
            ]);
        }
        else {
            return response()->json([
                'statuscode' => 404,
                'message' => 'Teams not found',
            ]);
        }
    }

    /**
     * @OA\Post(
     *      tags={"Teams"},
     *      path="/api/v1/teams",
     *      summary="Store a new team",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name", 
     *                      type="string", 
     *                      format="text", 
     *                      example="Bekasi FC"
     *                  ),
     *                  @OA\Property(
     *                      property="founded_year", 
     *                      type="integer", 
     *                      format="text", 
     *                      example="2000"
     *                  ),
     *                  @OA\Property(
     *                      property="address", 
     *                      type="string", 
     *                      format="text", 
     *                      example="Jl hayam wuruk no 67"
     *                  ),
     *                  @OA\Property(
     *                      property="city", 
     *                      type="string", 
     *                      format="text", 
     *                      example="Jakarta"
     *                  ),
     *                  @OA\Property(
     *                      description="Upload Team's Logo",
     *                      property="logo",
     *                      type="string",
     *                      format="file",
     *                  ),
     *              )
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
        try {
            $request->validate([
                'name' => 'required|string',
                'founded_year' => 'required|integer',
                'address' => 'required|string',
                'city' => 'required|string',
            ]);
        
            $team = Team::create($request->all());
                return response()->json([
                    "statuscode" => 201,
                    "message" => "Team has been added successfully"
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
     *      tags={"Teams"},
     *      path="/api/v1/teams/{id}",
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
     *                  example="Team found"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object", 
     *                  @OA\Property(
     *                      property="id", 
     *                      type="integer", 
     *                      example="2"
     *                  ),
     *                  @OA\Property(
     *                      property="name", 
     *                      type="string", 
     *                      example="Bekasi FC"
     *                  ),
     *                  @OA\Property(
     *                      property="logo", 
     *                      type="string", 
     *                      example="bekasi.png"
     *                  ),
     *                  @OA\Property(
     *                      property="founded_year", 
     *                      type="integer", 
     *                      example="2004"
     *                  ),
     *                  @OA\Property(
     *                      property="address", 
     *                      type="string", 
     *                      example="Bekasi"
     *                  ),
     *                  @OA\Property(
     *                      property="city", 
     *                      type="string", 
     *                      example="Tambun"
     *                  ),
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
        $team = Team::find($id);

        if (!$team) {
            return response()->json([
                'statuscode' => 404,
                'message' => 'Team not found'
            ], 404);
        }
        else {
            return response()->json([
                'statuscode' => 200,
                'message' => 'Team found',
                'data' => $team
            ], 200);
        }

        return response()->json($team, 200);
    }


    /**
     * @OA\Put(
     *      tags={"Teams"},
     *      path="/api/v1/teams/{id}",
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
     *              required={"name", "founded_year", "address", "city"},
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
            return response()->json([
                "statuscode" => 404,
                'message' => 'Team not found'
            ], 404);
        }

        try {
            $request->validate([
                'name' => 'sometimes|required|string',
                'founded_year' => 'sometimes|required|integer',
                'address' => 'sometimes|required|string',
                'city' => 'sometimes|required|string',
            ]);

            $team->update($request->all());

            return response()->json(['message' => 'Team updated successfully', 'team' => $team], 200);
        } catch (\Illuminate\Validation\ValidationException $th) {

            return response()->json([
                "statuscode" => 422,
                "message" => $th->validator->errors()->first()  
            ], 422);
        }    
    }

    /**
     * @OA\Delete(
     *      tags={"Teams"},
     *      path="/api/v1/teams/{id}",
     *      summary="Delete team",
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
            return response()->json([
                "statuscode" => 404,
                'message' => 'Team not found'
            ], 404);
        }

        $team->delete();

        return response()->json([
            "statuscode" => 200,
            'message' => 'Team deleted successfully'
        ], 200);
    }

}
