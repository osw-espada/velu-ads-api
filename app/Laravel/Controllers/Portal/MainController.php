<?php

namespace App\Laravel\Controllers\Portal;

use App\Laravel\Requests\PageRequest;

class MainController extends Controller{
    public function __construct()
    {

    }

    public function index(PageRequest $request){
        echo 1;
    }

}
