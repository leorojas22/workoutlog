<?php
namespace App\Helper;

use UnexpectedValueException;
use App\Exception\InvalidAuthTokenException;
use Symfony\Component\HttpFoundation\Request;

class AuthToken
{

    private $expTime;
    private $token;

    public function __construct(string $token, int $expTime = 0)
    {
        $this->token = htmlentities($token);
        $this->expTime = $expTime ?: static::getNewExpireTime();

        if(empty($this->token))
        {
            throw new UnexpectedValueException("Missing token value.");
        }
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return integer
     */
    public function getExpTime(): int
    {
        return $this->expTime;
    }

    /**
     * @param integer $expTime
     * @return AuthToken
     */
    public function setExpTime(int $expTime): AuthToken
    {
        $this->expTime = $expTime;
        return $this;
    }

    /**
     * Saves an auth token cookie
     *
     * @return void
     */
    public function saveCookie(): void
    {
        $cookieData = [
            'token' => $this->getToken(),
            'exp' => $this->getExpTime()
        ];

        setcookie("authToken", static::encodeToken($cookieData), $cookieData['exp'], "/", getenv("SITE_DOMAIN"), getenv("APP_ENV") === "prod", true);
    }

    public static function generate(): string
    {
        try
        {
            // If the token already exists in cookies, return that one
            if(!empty($_COOKIE['authToken']))
            {
                $authToken = static::decodeToken($_COOKIE['authToken']);
                return $authToken->getToken();
            }
        }
        catch(UnexpectedValueException $e)
        {
            // Missing token, generate a new one
        }


        // Create a random token
        $bytes = openssl_random_pseudo_bytes(32);
        $token = bin2hex($bytes);

        $authToken = new AuthToken($token);
        $authToken->saveCookie();

        return $authToken->getToken();
    }

    public static function refreshToken(): bool
    {
        if(empty($_COOKIE['authToken']))
        {
            return false;
        }

        // Check if we need to refresh the token
        try
        {
            $authToken = static::decodeToken($_COOKIE['authToken']);

            // Check if the token is expiring soon
            if($authToken->getExpTime() - time() < 300)
            {
                // Refresh
                $authToken->setExpTime(static::getNewExpireTime());
                $authToken->saveCookie();
                return true;
            }
        }
        catch(UnexpectedValueException $e)
        {
            // Do nothing
        }


        return false;
    }

    /**
     * Encodes token data to be stored in a cookie
     *
     * @param array $tokenData
     * @return string
     */
    protected function encodeToken(array $tokenData): string
    {
        return base64_encode(json_encode($tokenData));
    }

    protected function decodeToken(string $encodedData): AuthToken
    {
        $tokenData = json_decode(base64_decode($encodedData), true);

        $authToken = new AuthToken((string) $tokenData['token'] ?? "", (int) $tokenData['exp'] ?? 0);

        return $authToken;
    }

    /**
     * Gets the cookie expiration time for a new auth token cookie
     *
     * @return integer
     */
    protected static function getNewExpireTime(): int
    {
        return time() + (60*60*3);
    }

    public static function verifyRequest(Request $request)
    {
        // Refresh the auth token if needed on every request
        AuthToken::refreshToken();

        if($request->isMethodSafe())
        {
            // Continue as usual
            return;
        }


        // Check that there's a auth token passed
        $authToken = AuthToken::generate();

        if($authToken !== $request->headers->get("X-AUTH-TOKEN"))
        {
            throw new InvalidAuthTokenException();
        }
    }
}
