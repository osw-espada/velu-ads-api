<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */

use App\Laravel\Models\Transaction;
use App\Laravel\Requests\PageRequest;
use App\Laravel\Traits\ResponseGenerator;

use App\Laravel\Transformers\{TransactionTransformer, TransformerManager, UserTransformer};


class TransactionController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
    }

    public function index(PageRequest $request){
        $transactions = Transaction::paginate(env("DEFAULT_PAGINATION",10));

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "TRANSACTION_LIST";
        $this->response['msg'] = "Transaction list.";
        $this->response['data'] = $this->transformer->transform($transactions, new TransactionTransformer,'collection');
        $this->response = array_merge($this->response, $this->response_pagination($transactions));
        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function delete(PageRequest $request, $id = NULL)
    {
        $transaction = Transaction::find($id);

        if(!$transaction){
            $this->response['status'] = FALSE;
            $this->response['status_code'] = "NOT_FOUND";
            $this->response['msg'] = "Transaction not found.";
            $this->response_code = 404;
            goto callback;
        }

        $transaction->delete();

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "TRANSACTION_DELETED";
        $this->response['msg'] = "Transaction deleted.";
        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }

    public function destroy(PageRequest $request){
        Transaction::delete();
        $this->response['status'] = TRUE;
        $this->response['status_code'] = "ALL_TRANSACTION_DELETED";
        $this->response['msg'] = "All transactions deleted.";
        $this->response_code = 200;

        callback:
        return response()->json($this->api_response($this->response), $this->response_code);
    }
}
