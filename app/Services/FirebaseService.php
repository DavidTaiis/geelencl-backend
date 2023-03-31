<?php
namespace App\Services;

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Auth;
use Lcobucci\JWT\Token;

class FirebaseService
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $idToken
     * @return Token
     */
    public function verifyIdToken($idToken)
    {
        try {
            $checkIfRevoked = true;
            $verifiedIdToken = $this->auth->verifyIdToken($idToken, $checkIfRevoked);
//            $uid = $verifiedIdToken->getClaim('sub');
            return $verifiedIdToken;
        } catch (InvalidToken $e) {
            throw $e;
        }
    }

}
