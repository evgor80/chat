<?php

namespace App\Jwt;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwToken
{
    /**
     * Generate a new JWT token with given user data
     *
     * @param  User $user
     * @return string
     */
    public function generateJwt(User $user)
    {
        $key = config('jwt.secret');
        $payload = [
            'username' => $user->username,
            'id' => $user->id,
            'iat' => Carbon::now()->getTimestamp(),
            'exp' => Carbon::now()->addMinutes(config('jwt.minutes'))->getTimestamp(),
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }

    /**
     * Validate provided token and return decoded data from it
     * 
     * @param string $jwt
     * @return \stdClass
     */
    public function verifyJwt(string $jwt)
    {
        $key = config('jwt.secret');
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        return $decoded;
    }
}
