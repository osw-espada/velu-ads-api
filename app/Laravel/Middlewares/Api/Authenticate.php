<?php

namespace App\Laravel\Middlewares\Api;

use App\Laravel\Traits\ResponseGenerator;
use Helper;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;

class Authenticate extends BaseMiddleware
{
    use ResponseGenerator;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next,$guard="api")
    {

        try {
            // if (! $token = auth($guard)->setRequest($request)->getToken()) {
            if (! $token = $request->bearerToken()) {

                return $this->respond('jwt.absent', 'token_not_provided', 400);
            }

            $user = auth($guard)->authenticate($token);

        } catch (TokenExpiredException $e) {
            return $this->respond('jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        } catch (TokenBlacklistedException $e) {
            return $this->respond('jwt.expired', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('jwt.user_not_found', 'user_not_found', 404);
        }

        // event( new UserAction($user, ['update_device']) );

        // $request->get('device_reg_id');
        // $request->get('device_name');
        // $request->get('device_id');


        return $next($request);
    }


    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {

        $response = array();

        switch ($error) {
            case 'token_not_provided' :
                $response = [
                    'msg' => "Token not provided",
                    'status' => FALSE,
                    'status_code' => "TOKEN_NOT_PROVIDED",
                    'hint' => "You can obtain a token in a successful login/register request.",
                ];
                break;
            case 'token_expired' :
                $response = [
                    'msg' => "Your session has expired.",
                    'status' => FALSE,
                    'status_code' => "TOKEN_EXPIRED",
                    'hint' => "You must try refreshing your token. If this error still occurs, you must re-login.",
                ];
                break;
            case 'token_invalid' :
                $response = [
                    'msg' => "Invalid token",
                    'status' => FALSE,
                    'status_code' => "TOKEN_INVALID",
                    'hint' => "You can obtain a token in a successful login/register request.",
                ];
                break;
            case 'user_not_found' :
                $response = [
                    'msg' => "Invalid acccount access.",
                    'status' => FALSE,
                    'status_code' => "INVALID_AUTH_USER",
                ];
                break;
        }

        // $successful = $this->events->fire($event, $payload, true);

        // if($successful) {
        //     return $successful;
        // }
        return response()->json($this->api_response($response), 401);
    }
}
