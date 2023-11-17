<?php

namespace App\Support\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Support\Basket\Basket;
use App\Support\Gateways\GatewayInterface;
use App\Support\Gateways\Pasargad;
use App\Support\Gateways\Saman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Transaction
{
    private $request;
    private $basket;

    public function __construct(Request $request, Basket $basket)
    {
        $this->request = $request;
        $this->basket = $basket;
    }

    public function checkout()
    {
        $order = $this->makeOrder();

        $payment = $this->makePayment($order);

        if ($payment->isOnline()) {
            return $this->gateWayFactory()->pay($order);
        }

        $this->normalizeQuantity($order);

        $this->basket->clear();

        return $order;
    }

    public function verify()
    {
        $result = $this->gatewayFactory()->verify($this->request);

        if ($result['status'] === GatewayInterface::TRANSACTION_FAILED) return false;

        $this->confirmPayment($result);

        $this->normalizeQuantity($result['order']);

        $this->basket->clear();

        return true;
    }

    private function makeOrder()
    {
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'code' => bin2hex(Str::random('16')),
            'amount' => $this->basket->subTotal()
        ]);

        $order->products()->attach($this->products());

        return $order;
    }

    private function products(): array
    {
        $products = [];
        foreach ($this->basket->all() as $product) {
            $products[$product->id] = ['quantity' => $product->quantity];
        }

        return $products;
    }

    private function makePayment($order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'method' => $this->request->method,
            'amount' => $order->amount
        ]);
    }

    private function gateWayFactory()
    {
        $gateway = [
            'saman' => Saman::class,
            'pasargad' => Pasargad::class
        ][$this->request->gateway];

        return resolve($gateway);
    }

    private function normalizeQuantity($order)
    {
        foreach ($order->products as $product) {
            $product->decrementStock($product->pivot->quantity);
        }
    }

    private function confirmPayment($result)
    {
        $result['order']->payment->confirm($result['refNum'], $result['gateway']);
    }
}
