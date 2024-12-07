<?php

namespace App\Http\Controllers;

 /**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Soccer",
 *      description="API Soccer.",
 *      @OA\Contact(
 *          name="PT XYZ",
 *          url="https://xyz.test"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      ),
 *      @OA\Tag(
 *          name="Auth",
 *          description="Auth endpoints",
 *      ),
 *      @OA\SecurityScheme(
 *          type="http",
 *          securityScheme="bearerAuth",
 *          scheme="bearer",
 *          bearerFormat="JWT",
 *          securityScheme="apiAuth"
 *      )
 * )
 */

abstract class Controller
{
    //
}
