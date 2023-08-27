<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

final class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/v1/auth/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *           type="string",
     *           default="admin@admin.com"
     *      )
     *   ),
     *
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *
     *      @OA\Schema(
     *          type="string",
     *          default="password",
     *          format="password"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     *
     * @throws AuthenticationException
     */
    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || ($user && Hash::check($user->password, $request->password))) {
            throw new AuthenticationException();
        }
        $tokenResult = $user->createToken('Laravel Password Grant Client');
        $token = $tokenResult->token;
        $token->save();

        return response()->success(
            [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ]
        );
    }

    /**
     * @OA\Get (
     * path="/api/v1/auth/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="authLogout",
     * tags={"Auth"},
     * security={{"bearerAuth":{}}},
     *
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *
     *    @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )
     * )
     * )
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->success();
    }
}
