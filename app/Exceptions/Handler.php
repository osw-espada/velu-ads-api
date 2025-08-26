<?php

namespace App\Exceptions;

use App\Laravel\Traits\ResponseGenerator;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Configuration\Exceptions;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseGenerator;
    public static function registerInExceptions(Exceptions $exceptions): void
    {
        $exceptions->renderable(function (Throwable $exception, $request){
            $api_response = false;
            if(in_array(strtolower($request->segment(1)), ["api","wh"]) == "api" OR $request->header('host') == env("API_URL","") ){
                $api_response = true;
            }

            switch(get_class($exception)){
                case "libphonenumber\NumberParseException":
                    $response = array(
                        "msg" => "Invalid Phone number format.",
                        "status" => FALSE,
                        'status_code' => "INVALID_FORMAT"
                    );
                    $status_code = 419;

                    break;
                case "Illuminate\Session\TokenMismatchException":

                    $response = array(
                        "msg" => "Token expired. Please try again",
                        "status" => FALSE,
                        'status_code' => "INVALID_TOKEN"
                    );
                    $status_code = 401;

                    break;
                case "ParseError":
                case "Error":
                case "UnexpectedValueException":
                case "BadMethodCallException":
                case "ErrorException":
                case "ReflectionException":
                case "Symfony\Component\Debug\Exception\FatalErrorException":
                case "Symfony\Component\Debug\Exception\FatalThrowableError":
                case "InvalidArgumentException":
                case "GuzzleHttp\Exception\ClientException":
                case "Illuminate\Contracts\Container\BindingResolutionException":
                case "Exception":
                case "TypeError":
                case "Facade\Ignition\Exceptions\ViewException":
                case "Symfony\Component\ErrorHandler\Error\FatalError":
                case "Symfony\Component\Routing\Exception\RouteNotFoundException":
                case "PHPOpenSourceSaver\JWTAuth\Exceptions\SecretMissingException":
                    $response = array(
                        "msg" => "Server error. Code : #{$exception->getLine()}",
                        "status" => FALSE,
                        'status_code' => "APP_ERROR",
                    );
                    $status_code = 500;
                    break;
                case "PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException":
                    $response = array(
                        "msg" => "Invalid Token. Unable to proceed request.",
                        "status" => FALSE,
                        'status_code' => "UNAUTHORIZED",
                    );
                    $status_code = 401;

                    break;
                case "Propaganistas\LaravelPhone\Exceptions\NumberParseException":
                    $response = array(
                        "msg" => "Invalid Phone number.",
                        "status" => FALSE,
                        'status_code' => "APP_ERROR",
                    );
                    $status_code = 400;
                    break;
                case "Illuminate\Database\QueryException":
                    $response = array(
                        "msg" => "Database error. Code : #{$exception->getLine()}",
                        "status" => FALSE,
                        'status_code' => "DB_ERROR"
                    );
                    $status_code = 500;
                    break;
                case "Symfony\Component\HttpKernel\Exception\NotFoundHttpException":
                    $status_code = $exception->getStatusCode();
                    $response = array(
                        "msg" => "METHOD : {$request->server()["REQUEST_METHOD"]},API : {$request->getRequestUri()} not found.",
                        "status" => FALSE,
                        'status_code' => "NOT_FOUND"
                    );

                    break;
                case "Tymon\JWTAuth\Exceptions\TokenBlacklistedException":
                    $response = array(
                        "msg" => "Invalid/Expired token.",
                        "status" => FALSE,
                        'status_code' => "INVALID_TOKEN"
                    );
                    $status_code = 401;
                    break;
                case "Illuminate\Auth\AuthenticationException":
                    $response = array(
                        "msg" => "Session expired.",
                        "status" => FALSE,
                        'status_code' => "ACCOUNT_LOGOUT"
                    );
                    $status_code = 401;
                    break;
                case "Tymon\JWTAuth\Exceptions\TokenExpiredException":
                    $response = array(
                        "msg" => "Expired token.",
                        "status" => FALSE,
                        'status_code' => "EXPIRED_TOKEN"
                    );
                    $status_code = 401;
                    break;
                case "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException":
                    $status_code = $exception->getStatusCode();

                    $response = array(
                        "msg" => "{$request->server()["REQUEST_METHOD"]} METHOD API : {$request->getRequestUri()} not allowed.",
                        "status" => FALSE,
                        'status_code' => "METHOD_NOT_ALLOWED"
                    );
                    break;

                case "Illuminate\Http\Exceptions\PostTooLargeException":
                    $response = array(
                        "msg" => "Unable to process attachment. File too large.",
                        "status" => FALSE,
                        'status_code' => "UPLOAD_SIZE_LIMIT_REACHED"
                    );
                    $status_code = 406;
                    break;
                case "Illuminate\Foundation\Http\Exceptions\MaintenanceModeException":
                    $response = array(
                        "msg" => "Platform currently under maintenance.",
                        "status" => FALSE,
                        'status_code' => "MAINTENANCE_MODE"
                    );
                    $status_code = 503;
                    break;
                default:
                    if (get_class($exception) == "Symfony\Component\HttpKernel\Exception\HttpException"){

                        if($api_response) {
                            $response = array(
                                "msg" => "Platform currently under maintenance.",
                                "status" => FALSE,
                                'status_code' => "MAINTENANCE_MODE"
                            );

                            if($request->input('hint')){
                                $response['hint'] = $exception->getMessage();
                            }
                            return response()->json(self::api_response($response), 503);
                        }

                        return response()->view("custom::errors.503", [], 503);
                    }else{
                        dd($exception);
                    }

            }

            callback:
            if($api_response){
                $response['code'] = "#".$exception->getLine();

                if($request->input('hint')){
                    $response['hint'] = $exception->getMessage();
                }
                return response()->json(self::api_response($response), $status_code);
            }

            session()->flash('notification-status', "warning");
            session()->flash('notification-msg', $response['msg']);
            if(!in_array($status_code,["404","500"])){
                return redirect()->back();
            }

            $status = $status_code;

            if (view()->exists("custom::errors.$status")) {
                return response()->view("custom::errors.$status", [], $status);
            }
            // Optional generic error view fallback
            if (view()->exists("custom::errors.error")) {
                return response()->view("custom::errors.error", [], $status);
            }

        });



            // Add all other renderable/reportable logic from your old Handler render method here
        // For fallback or default, you can catch Throwable
    }

}

