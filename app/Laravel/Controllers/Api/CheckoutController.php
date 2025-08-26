<?php

namespace App\Laravel\Controllers\Api;

/*
 * Request Validator
 */

use App\Laravel\Models\Transaction;
use App\Laravel\Requests\PageRequest;
use App\Laravel\Requests\Web\CheckoutRequest;
use App\Laravel\Traits\ResponseGenerator;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Laravel\Transformers\{TransformerManager};

use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;


class CheckoutController extends Controller
{
    use ResponseGenerator;

    protected $data, $guard,$response, $response_code, $transformer;

    public function __construct()
    {
        parent::__construct();
        $this->transformer = new TransformerManager;
        $this->guard = "api";
    }

    public function generate(CheckoutRequest $request)
    {
        $transaction = new Transaction();
        $transaction->code = Str::uuid();
        $transaction->reference_number = $request->input('reference_number');
        $transaction->description = $request->input('description');
        $transaction->subtotal = $request->input('amount');
        $transaction->total = $transaction->subtotal;
        $transaction->save();

        $transaction->save();
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $transaction->description,
                    ],
                    'unit_amount' => $transaction->total*100, // $10 in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('web.stripe.success',$transaction->code),
            'cancel_url' => route('web.stripe.cancel',$transaction->code),
        ]);

        $transaction->payment_id = $session->id;
        $transaction->checkout_url = $session->url;
        $transaction->payment_object = $session->object;
        $transaction->payment_status = $session->payment_status;
        $transaction->status = $session->status;
        $transaction->expired_at = Carbon::parse($session->expires_at)->format("Y-m-d H:i:s");
        $transaction->save();

        $this->response['status'] = TRUE;
        $this->response['status_code'] = "CHECKOUT_URL";
        $this->response['msg'] = "Checkout URL generated";
        $this->response['checkout_url'] = $transaction->checkout_url;
        $this->response_code = 200;
        callback:
        return response()->json($this->api_response($this->response), $this->response_code);

    }
}
