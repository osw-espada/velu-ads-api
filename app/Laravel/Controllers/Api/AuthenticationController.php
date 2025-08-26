<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */

use App\Laravel\Models\User;
use App\Laravel\Requests\Api\RegisterRequest;
use App\Laravel\Requests\PageRequest;
use App\Laravel\Traits\ResponseGenerator;

use App\Laravel\Transformers\{UserTransformer, TransformerManager};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/* App Classes
 */

class AuthenticationController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
        $this->response = array(
            "msg" => "Bad Request.",
            "status" => FALSE,
            'status_code' => "BAD_REQUEST"
        );
        $this->response_code = 400;
    }

    public function register(RegisterRequest $request,$format = NULL){

        DB::beginTransaction();
        try{

            $user = new User();
            $user->email = strtolower($request->input('email'));
            $user->name = strtoupper($request->input('name'));
            $user->password = bcrypt($request->input('password'));
            $user->save();

            DB::commit();

            $this->response['status'] = TRUE;
            $this->response['status_code'] = "REGISTERED";
            $this->response['msg'] = "Successfully registered.";
            $this->response_code = 201;
        }catch(\Exception $e){
            DB::rollback();
            $this->response['status'] = FALSE;
            $this->response['status_code'] = "SERVER_ERROR";
            $this->response['msg'] = "Server Error: Code #{$e->getMessage()}";
            $this->response_code = 500;
        }

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function login(PageRequest $request){
        $password  = $request->input('password');
        $value =  strtolower($request->input('email'));

        /* Replace else value to username if username exist */
        $field = filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'email';

        $password  = $request->input('password');

        if(!$token = auth($this->guard)->attempt([$field => $value,'password' => $password])){
            $this->response['status'] = FALSE;
            $this->response['status_code'] = "UNAUTHORIZED";
            $this->response['msg'] = "Invalid account credentials.";
            $this->response_code = 401;
            goto  callback;
        }

        $user =  auth($this->guard)->user();

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "LOGIN_SUCCESS";
        $this->response['msg'] = "Hi {$user->name}!";
        $this->response['token'] = $token;
        $this->response['token_type'] = "Bearer";
        $this->response['data'] = $this->transformer->transform($user, new UserTransformer,'item');

        $this->response_code = 200;
        goto callback;

        $this->response['status'] = FALSE;
        $this->response['msg'] = "Invalid account credentials";

        if($login_type == "phone"){
            $this->response['status_code'] = "INVALID_PHONE_NUMBER";
        }else{
            $this->response['status_code'] = "INVALID_EMAIL";
        }
        $this->response_code = 412;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function check_login(PageRequest $request){
        $user = auth($this->guard)->user();

        if(!$user){
            $this->response['status'] = FALSE;
            $this->response['status_code'] = "UNAUTHORIZED";
            $this->response['msg'] = "Invalid/Expired token. Do refresh token.";
            $this->response_code = 401;
            goto  callback;
        }

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "LOGIN_SUCCESS";
        $this->response['msg'] = "Welcome {$user->name}!";
        $this->response['data'] = $this->transformer->transform($user,new UserTransformer,'item');

        $this->response_code = 200;
        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function refresh_token(PageRequest $request){

        $old_token = $request->bearerToken();

        $user =  auth($this->guard)->user();

        $new_token = auth($this->guard)->refresh();

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "ACCESS_TOKEN_UPDATED";
        $this->response['msg'] = "New access token assigned.";
        $this->response['token'] = $new_token;
        $this->response['token_type'] = "Bearer";

        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function logout(PageRequest $request){

        $user = auth($this->guard)->user();
        if($user){
            auth($this->guard)->logout(true);

            $this->response['status'] = TRUE;
            $this->response['status_code'] = "LOGOUT_SUCCESS";
            $this->response['msg'] = "Session closed.";
            $this->response_code = 200;
            goto callback;
        }

        $this->response['status_code'] = "UNAUTHORIZED";
        $this->response['msg'] = "No session found.";

        $this->response_code = 401;


        callback:
        return response()->json($this->api_response($this->response), $this->response_code);

    }
}
