<?php

namespace App\Support\Payment;

use App\Events\OrderRegistered;
use App\Models\Order;
use App\Models\Payment;
use App\Support\Basket\Basket;
use App\Support\Gateways\GatewayInterface;
use App\Support\Gateways\Pasargad;
use App\Support\Gateways\Saman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            $order = $this->makeOrder();
            $payment = $this->makePayment($order);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }

        if ($payment->isOnline()) return $this->gatewayFactory()->pay($order);

        $this->completeOrder($order);

        return $order;
    }

    public function verify(): bool
    {
        $result = $this->gatewayFactory()->verify($this->request);

        if ($result['status'] === GatewayInterface::TRANSACTION_FAILED) return false;

        $this->confirmPayment($result);

        $this->completeOrder($result['order']);

        return true;
    }

    private function completeOrder($order)
    {
        $this->normalizeQuantity($order);

        event(new OrderRegistered($order));

        $this->basket->clear();
    }

    private function normalizeQuantity($order)
    {
        foreach ($order->products as $product) {
            $product->decrementStock($product->pivot->quantity);
        }
    }

    private function confirmPayment($result): void
    {
        $result['order']->payment->confirm($result['refNum'], $result['gateway']);
    }

    private function gatewayFactory()
    {
        $gateway = [
            'saman' => Saman::class,
            'pasargad' => Pasargad::class
        ]
        [$this->request->gateway];

        return resolve($gateway);
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

    private function makePayment($order)
    {
        return Payment::create([
            'order_id' => $order->id,
            'method' => $this->request->method,
            'amount' => $order->amount
        ]);
    }

    private function products(): array
    {
        foreach ($this->basket->all() as $product) {
            $products[$product->id] = ['quantity' => $product->quantity];
        }

        return $products;
    }
}
