<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */

use App\Laravel\Models\Article;
use App\Laravel\Requests\Api\ArticleRequest;
use App\Laravel\Requests\PageRequest;
use App\Laravel\Services\ImageUploader;
use App\Laravel\Traits\ResponseGenerator;

use App\Laravel\Transformers\{ArticleTransformer, TransformerManager};
use Illuminate\Support\Facades\DB;

/* App Classes
 */

class ArticleController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
    }

    public function index(PageRequest  $request){
        $per_page = $request->input('per_page',10);
        $auth = $request->user($this->guard);

        $articles = Article::where("user_id", $auth->id)
            ->orderBy('updated_at',"DESC")
            ->paginate($per_page);

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "ARTICLE_LIST";
        $this->response['msg'] = "Article list.";
        $this->response = array_merge($this->response, $this->response_pagination($articles));
        $this->response['data'] = $this->transformer->transform($articles,new ArticleTransformer(),'collection');
        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function show(PageRequest $request){
        $article = $request->get('article_data');

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "ARTICLE_DETAIL";
        $this->response['msg'] = "Article detail.";
        $this->response['data'] = $this->transformer->transform($article, new ArticleTransformer, 'item');
        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function store(ArticleRequest $request){
        $auth = $request->user($this->guard);

        DB::beginTransaction();
        try{

            $article = new Article;
            $article->user_id = $auth->id;
            $article->name = $request->input('name');
            $article->description = $request->input('description');

            if ($request->hasFile('image')) {
                $image = ImageUploader::uploadAutoResize($request->file('image'), "uploads/articles/images");

                $article->path = $image['path'];
                $article->directory = $image['directory'];
                $article->filename = $image['filename'];
                $article->source = $image['source'];
            }

            $article->save();

            DB::commit();

            $this->response['status'] = TRUE;
            $this->response['status_code'] = "ARTICLE_CREATED";
            $this->response['msg'] = "Article was successfully created.";
            $this->response_code = 201;
        }catch(\Exception $e){
            DB::rollback();

            Log::info("ERROR: ", array($e));

            $this->response['status'] = FALSE;
            $this->response['status_code'] = "SERVER_ERROR";
            $this->response['msg'] = "Server Error: Code #{$e->getMessage()}";
            $this->response_code = 500;
        }

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function update(ArticleRequest $request){
        $auth = $request->user($this->guard);
        $article = $request->get('article_data');

        DB::beginTransaction();
        try{

            $article->user_id = $auth->id;
            $article->name = $request->input('name');
            $article->description = $request->input('description');

            if ($request->hasFile('image')) {
                $image = ImageUploader::uploadAutoResize($request->file('image'), "uploads/articles/images");

                $article->path = $image['path'];
                $article->directory = $image['directory'];
                $article->filename = $image['filename'];
                $article->source = $image['source'];
            }

            $article->save();

            DB::commit();

            $this->response['status'] = TRUE;
            $this->response['status_code'] = "ARTICLE_UPDATED";
            $this->response['msg'] = "Article was successfully updated.";
            $this->response['data'] = $this->transformer->transform($article, new ArticleTransformer, 'item');
            $this->response_code = 200;
        }catch(\Exception $e){
            DB::rollback();

            Log::info("ERROR: ", array($e));

            $this->response['status'] = FALSE;
            $this->response['status_code'] = "SERVER_ERROR";
            $this->response['msg'] = "Server Error: Code #{$e->getMessage()}";
            $this->response_code = 500;
        }

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function destroy(PageRequest $request){
        $article = $request->get('article_data');

        DB::beginTransaction();
        try{
            $article->delete();

            DB::commit();

            $this->response['status'] = TRUE;
            $this->response['status_code'] = "ARTICLE_DELETED";
            $this->response['msg'] = "Article was successfully deleted.";
            $this->response_code = 200;
        }catch(\Exception $e){
            DB::rollback();

            Log::info("ERROR: ", array($e));

            $this->response['status'] = FALSE;
            $this->response['status_code'] = "SERVER_ERROR";
            $this->response['msg'] = "Server Error: Code #{$e->getMessage()}";
            $this->response_code = 500;
        }

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }
}
