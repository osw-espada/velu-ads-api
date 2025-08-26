<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */
use App\Laravel\Requests\PageRequest;
use App\Laravel\Traits\ResponseGenerator;

use App\Laravel\Transformers\{TransformerManager};


class SettingController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
    }

    public function health(PageRequest $request)
    {
        $this->response['status'] = TRUE;
        $this->response['status_code'] = "HEALTH_CHECK";
        $this->response['msg'] = "Health check status";
        $this->response['data'] = [
            'version' => env("APP_VERSION","0.1"),
            'build' => env("APP_BUILD_NUMBER",1),
        ];
        $this->response_code = 200;
        callback:
        return response()->json($this->api_response($this->response), $this->response_code);

    }
}
