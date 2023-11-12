<?php

namespace App\Http\Controllers;

use App\Exceptions\QuantityExceededException;
use App\Models\Product;
use App\Support\Basket\Basket;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    private $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    public function index()
    {
        $items = $this->basket->all();
        return view('basket', compact('items'));
    }

    public function add(Product $product): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->basket->add($product, 1);

            return back()->with('success', __('payment.added to basket'));

        } catch (QuantityExceededException $e) {
            return back()->with('error', __('payment.quantity exceeded'));
        }
    }

    public function update(Request $request, Product $product): \Illuminate\Http\RedirectResponse
    {
        $this->basket->update($product, $request->quantity);
        return back();
    }

    public function checkoutForm()
    {
        return view('checkout');
    }

    public function checkout(Request $request)
    {
        $this->validateForm($request);
    }

    private function validateForm($request)
    {
        $request->validate([
            'method' => ['required'],
            'getway' => ['required_if:method,online']
        ]);
    }
}
