<?php

namespace App\Processes;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Validators\LoginValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginProcess
{
    /**
     * @var LoginValidator
     */
    private $loginValidator;


    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * loginProcess constructor.
     *
     * @param LoginValidator $loginValidator
     * @param LoginRepository $loginRepository
     */
    public function __construct(LoginValidator $loginValidator,UserRepository $userRepository)
    {
        $this->loginValidator = $loginValidator;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($credentials)
    {
        $this->loginValidator->email($credentials);
        $this->loginValidator->password($credentials);
    
        $user = $this->userRepository->getUser($credentials['email']);
        $this->loginValidator->role($user,$credentials);
        $user->save();
       
        $tokenResult = $user->createToken(config('constants.oauthPassport.tokenAccessClient'));
        $token = $tokenResult->token;
        $token->save();
        
        return response()->json([
            'accessToken' => $tokenResult->accessToken,
            'tokenType' => 'Bearer',
            'expiresAt' => $token->expires_at,
            'expiresAtDate' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }
}
