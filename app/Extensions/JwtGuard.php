<?php

namespace App\Extensions;

use Firebase\JWT\JWT;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    protected $secret;

    public function __construct(UserProvider $provider, Request $request, string $secret) {
        $this->provider = $provider;
        $this->request = $request;
        $this->secret = $secret;
    }

    /**
     * Get user logged in
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (isset($this->user)) {
            return $this->user;
        }

        return $this->user = $this->provider->retrieveById(
            $this->payload()->uid ?? null
        );
    }

    /**
     * Set user login
     *
     * @param Authenticatable $user
     * @return $this
     */
    public function login(Authenticatable $user)
    {
        return $this->setUser($user);
    }

    /**
     * Generate jwt token for current user
     *
     * @param array $options
     * @param string $algo
     * @return string
     */
    public function generateJwt(array $options = [], string $algo = 'HS256')
    {
        $payload = [
            'uid' => $this->user->getAuthIdentifier(),
            'iat' => time()
        ];

        $payload = array_merge($payload, $options);

        return JWT::encode($payload, $this->secret, $algo);
    }

    /**
     * We don't need validate for jwt
     *
     * @param array $attr
     * @return false
     */
    public function validate(array $attr = [])
    {
        return false;
    }

    /**
     * Get payload from jwt
     *
     * @return object|null
     */
    public function payload()
    {
        try {
            return JWT::decode($this->getJwtFromRequest(), $this->secret, ['HS256']);
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * get jwt from request
     *
     * @return string|null
     */
    public function getJwtFromRequest()
    {
        return $this->request->bearerToken() ??
            $this->request->jwt ??
            $this->request->token;
    }
}
