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

    public function user()
    {
        if (isset($this->user)) {
            return $this->user;
        }

        return $this->user = $this->provider->retrieveById(
            $this->payload()->uid ?? null
        );
    }

    public function login(Authenticatable $user)
    {
        return $this->setUser($user);
    }

    public function generateJwt(array $options = [], string $algo = 'HS256')
    {
        $payload = [
            'uid' => $this->user->getAuthIdentifier(),
            'iat' => time()
        ];

        $payload = array_merge($payload, $options);

        return JWT::encode($payload, $this->secret, $algo);
    }

    public function validate(array $attr = [])
    {
        return false;
    }

    public function payload()
    {
        try {
            return JWT::decode($this->getJwtFromRequest(), $this->secret, ['HS256']);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getJwtFromRequest()
    {
        return $this->request->bearerToken() ??
            $this->request->jwt ??
            $this->request->token;
    }
}
