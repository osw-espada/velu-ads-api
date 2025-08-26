<?php

namespace App\Laravel\Transformers;

use App\Laravel\Models\User;
use League\Fractal\TransformerAbstract;

use App\Laravel\Traits\ResponseGenerator;

class UserTransformer extends TransformerAbstract{
    use ResponseGenerator;

    public function transform(User $user) {
        return [
            'user_id' => $user->id ?: 0,

            'name' => $user->name ?: "",

            'email' => $user->email ?:"",
            'date_registered' => $this->date_response($user->created_at),
            'avatar' => $this->image_response($user->directory,$user->filename)
        ];
    }
}
