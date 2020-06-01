<?php
namespace App\Helper;

use App\Entity\User;
use Firebase\JWT\JWT;

class JwtHelper
{

    /**
     * Generates jwt for the user and stores it in a cookie
     *
     * @param User $user
     * @param string $encodeKey
     * @return string
     */
    public static function applyJWTForUser(User $user): string
    {
        return static::applyJWT($user->getId(), $user->getEmail());
    }

    /**
     * Creates a jwt token and stores it in a cookie.
     *
     * @param integer $userId
     * @param string $email
     * @param string $encodeKey
     * @return string - the JWT encoded string
     */
    protected static function applyJWT(int $userId, string $email): string
    {
        // Expire time
        $expTime = time()+(3600*24);

        $tokenPayload = [
            'userId' => $userId,
            'email'  => $email,
            'exp'    => $expTime
        ];

        $jwt = JWT::encode($tokenPayload, getenv("JWT_SECRET"));

        setcookie("jwt", $jwt, $expTime, "/", getenv("SITE_DOMAIN"), getenv("APP_ENV") === "prod", true);

        return $jwt;
    }

    public static function removeJWTCookie()
    {
        // Set the cookie so it would be expired
        setcookie("jwt", null, time() - 3600, "/", getenv("SITE_DOMAIN"), getenv("APP_ENV") === "prod", true);
    }

    /**
     * Verifies that the jwt provided is valid
     *
     * @param string $jwt
     * @param string $encodeKey
     * @return integer|null
     */
    public static function verifyJWT(string $jwt): ?int
    {
        try
        {
            $decoded = JWT::decode($jwt, getenv("JWT_SECRET"), ['HS256']);

            // Successfully decoded token, check if it is expiring soon
            if($decoded->exp-time() < (3600*12))
            {
                // Token is expiring in 1 hour, refresh it to prevent an abrupt logout
                static::applyJWT($decoded->userId, $decoded->email);
            }

            return $decoded->userId;
        }
        catch(\Exception $e)
        {
            // Do nothing
        }

        return null;
    }
}
