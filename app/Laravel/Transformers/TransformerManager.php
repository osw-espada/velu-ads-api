<?php

namespace App\Laravel\Transformers;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Laravel\Services\DataArraySerializer;

class TransformerManager
{
    public function transform($data, $transformer, $type = 'item')
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());
        $request = Request();

        if($request->has('include')) {
            $manager->parseIncludes(str_replace(" ", "", $request->get('include')));
        }

        if($type == 'item'){
            $resource = new Item($data, $transformer);
        }else{
            $resource = new Collection($data, $transformer);
        }

        $data = $manager->createData($resource)->toArray();
        return $data;
    }
}
