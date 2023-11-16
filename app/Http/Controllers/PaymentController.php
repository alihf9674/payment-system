<?php

namespace App\Http\Controllers;

use App\Support\Payment\Transaction;

class PaymentController extends Controller
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function verify(): \Illuminate\Http\RedirectResponse
    {
        return $this->transaction->verify()
            ? $this->sendSuccessResponse()
            : $this->sendErrorResponse();
    }

    private function sendErrorResponse(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', 'مشکلی در هنگام ثبت سفارش به وجود آمده است');
    }

    private function sendSuccessResponse(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('success', 'سفارش شما با موفقیت ایجاد شد');
    }
}
