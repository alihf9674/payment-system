<?php

namespace App\Http\Controllers;

use App\Exceptions\QuantityExceededException;
use App\Models\Product;
use App\Support\Basket\Basket;

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
}
