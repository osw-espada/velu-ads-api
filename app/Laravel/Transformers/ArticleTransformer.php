<?php

namespace App\Laravel\Transformers;

use App\Laravel\Models\Article;
use League\Fractal\TransformerAbstract;

use App\Laravel\Traits\ResponseGenerator;

class ArticleTransformer extends TransformerAbstract{
    use ResponseGenerator;

    public function transform(Article $article) {
        return [
            'id' => $article->id ?:0,
            'name' => $article->name ?: "",
            'description' => $article->description ?: "",
            'date_created' => $this->date_response($article->created_at),
            'date_modified' => $this->date_response($article->updated_at),
            'image' => $this->image_response($article->directory,$article->filename)
        ];
    }
}
