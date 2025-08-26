<?php

namespace App\Laravel\Transformers;

use App\Laravel\Models\Article;
use App\Laravel\Models\Transaction;
use League\Fractal\TransformerAbstract;

use App\Laravel\Traits\ResponseGenerator;

class TransactionTransformer extends TransformerAbstract{
    use ResponseGenerator;

    public function transform(Transaction $transaction) {
        return [
            'id' => $transaction->id ?:0,
            'code' => $transaction->code??"",
            'reference_number' => $transaction->reference_number??"",
            'description' => $transaction->description ??"",
            'currency' => $transaction->currency,
            'subtotal' => money_format($transaction->subtotal),
            'total' => money_format($transaction->total),
            'payment_status' => $transaction->payment_status,
            'status' => $transaction->status,
            'checkout_url' => $transaction->checkout_url,
            'date_created' => $this->date_response($transaction->created_at),
            'date_modified' => $this->date_response($transaction->updated_at),
            'date_paid' => $this->date_response($transaction->paid_at),
            'date_expires' => $this->date_response($transaction->expired_at),
        ];
    }
}
