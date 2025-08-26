<?php namespace App\Laravel\Traits;

trait ResponseGenerator
{
    public function db_error(){
        $response['status'] = FALSE;
        $response['status_code'] = "DB_ERROR";
        $response['msg'] = "Process failed due to internal server error. Please try again.";
        $response_code = 500;

        return ['body' => $response, 'code' => $response_code];
    }

    public function not_found_error(){
        $response['status'] = FALSE;
        $response['status_code'] = "NOT_FOUND";
        $response['msg'] = "Record not found.";
        $response_code = 404;

        return ['body' => $response, 'code' => $response_code];
    }

    public function unauthorized_error(){
        $response['status'] = FALSE;
        $response['status_code'] = "UNAUTHORIZED";
        $response['msg'] = "Invalid account credentials.";
        $response_code = 401;

        return ['body' => $response, 'code' => $response_code];
    }

    public function money_response($value){
        $response = [
            'value' => money_db($value),
            'display_value' => money_format($value)
        ];

        return $response;
    }

    public function number_response($value){
        $response = [
            'value' => $value,
            'display_value' => nice_number($value)
        ];

        return $response;
    }

    public function date_response($date){
        $response = [
            'date_db' => "",
            'date_only' => "",
            'datetime_ph' => "",
            'date_only_ph' => "",
            'time_passed' => "",
            'timestamp' => "",
            "iso_format" => ""
        ];

        if($date){
            $response['date_db'] = $date->format("Y-m-d h:i a");
            $response['date_only'] = $date->format("Y-m-d");
            $response['datetime_ph'] = $date->format("m/d/Y h:i A");
            $response['date_only_ph'] = $date->format("m/d/Y");
            $response['time_passed'] = $date->diffForHumans();
            $response['timestamp'] = $date->toAtomString();
            $response['iso_format'] = $date->toISOString();
            $response['month_year'] = $date->format("M-Y");

        }
        return $response;
    }

    public static function api_response($data){
        $meta = [
            'meta' => [
                'copyright' => "Copyright © 2025 ".env("APP_NAME","Laravel 12"),
                'authors' => [
                    "RICHARD KENNEDY DOMINGO"
                ],
                'jsonapi' => [
                    'version' => env("APP_VERSION",0.1),
                    'build' => env("APP_BUILD_NUMBER",1)
                ]
            ]
        ];

        return $meta+$data;
    }

    public function image_response($directory,$filename){
        return [
            'thumbnail' => $filename ? "{$directory}/thumbnails/{$filename}" : "",
            'original' => $filename ? "{$directory}/resized/{$filename}" : "",
        ];
    }

    public function response_pagination($paginated_model){
        $response['count'] = $paginated_model->count();
        $response['total_result'] = $paginated_model->total();
        $response['per_page'] = $paginated_model->perPage();
        $response['current_page'] = $paginated_model->currentPage();
        $response['last_page'] = $paginated_model->lastPage();
        $response['has_more_pages'] = $paginated_model->hasMorePages();
        return $response;
    }
}
