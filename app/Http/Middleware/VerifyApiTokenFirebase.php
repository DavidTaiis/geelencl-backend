<?php

namespace App\Http\Middleware;

use App\Services\FirebaseService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\Response as IlluminateResponse;

class VerifyApiTokenFirebase
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if ($request->hasHeader('authorization') === false) {
                return response()->json(['code' => 401, 'status' => 'error', 'message' => "Missing 'Authorization' header"],
                    IlluminateResponse::HTTP_UNAUTHORIZED);
            }

            $this->firebaseAuthentication($request);
            return $next($request);
        } catch (Exception $e) {
            return response()->json(['code' => 401, 'status' => 'error', 'message' => $e->getMessage()],
                IlluminateResponse::HTTP_UNAUTHORIZED);
        }

    }

    /**
     * @param Request $request
     * @return Token
     * @throws Exception
     */
    private function firebaseAuthentication(Request $request)
    {
        $jwt = $request->bearerToken();
        try {
            return $this->firebaseService->verifyIdToken($jwt);
        } catch (Exception $e) {
            throw $e;
        }
    }

}
