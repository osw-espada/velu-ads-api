<?php

namespace App\Laravel\Traits;
use Ramsey\Uuid\Uuid;

trait UuidIdentifier
{
    public function getIncrementing(){
        return false;
    }

    public function getKeyType(){
        return 'string';
    }

    protected static function bootUuidIdentifier(){
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Uuid::uuid4();
            }
        });
    }
}
