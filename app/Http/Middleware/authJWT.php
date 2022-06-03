<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\JWT;

class authJWT extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return response()->json(['token_not_provided'], 400);
        }

        try {
            $user= JWTAuth::toUser(JWTAuth::getToken());
        } catch (TokenExpiredException $e) {
            return response()->json( ['token_expired'], 401);
        } catch (JWTException $e) {
            return response()->json( ['token_invalid'], 401);
        }
        catch (DecryptException $e)
        {
            return response()->json( ['invalid_format'], 401);
        }
        if (! $user) {
            return response()->json(['user_not_found'], 404);
        }

//        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}