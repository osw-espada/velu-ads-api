<?php

namespace App\Laravel\Controllers\Web;

use App\Laravel\Models\Transaction;
use App\Laravel\Requests\PageRequest;

use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class StripeController extends Controller{
    public function __construct()
    {

    }

    public function success(PageRequest $request,$code){

        $transaction = Transaction::where('code',$code)->first();


        $data['transaction_code'] = $code;
        if(!$transaction) return view('web.empty',$data);
        $data['transaction'] = $transaction;

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = CheckoutSession::retrieve($transaction->payment_id);
        if(!$session) return view('web.empty',$data);

        if($session->payment_status != "paid") return view('web.pending',$data);

        $transaction->payment_status = $session->payment_status;
        $transaction->status = $session->status;
        $transaction->expired_at = null;
        $transaction->paid_at = now();
        $transaction->save();

        $data['transaction'] = $transaction;

        return view('web.success',$data);

    }

    public function cancel(PageRequest $request,$code){

        $transaction = Transaction::where('code',$code)->first();
        $data['transaction_code'] = $code;
        if(!$transaction) return view('web.empty',$data);

        $transaction->payment_status = "cancelled";
        $transaction->status = "cancelled";
        $transaction->expired_at = null;
        $transaction->save();

        $data['transaction'] = $transaction;
        return view('web.cancelled',$data);
    }

}
