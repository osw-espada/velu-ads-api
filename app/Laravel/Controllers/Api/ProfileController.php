<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Traits\ResponseGenerator;

use App\Laravel\Transformers\{TransformerManager, UserTransformer};


class ProfileController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
    }

    public function show(PageRequest $request)
    {
        $user = auth($this->guard)->user();
        $this->response['status'] = TRUE;
        $this->response['status_code'] = "PROFILE_DETAIL";
        $this->response['msg'] = "Profile information";
        $this->response['data'] = $this->transformer->transform($user, new UserTransformer,'item');
        $this->response_code = 200;
        callback:
        return response()->json($this->api_response($this->response), $this->response_code);

    }
}
