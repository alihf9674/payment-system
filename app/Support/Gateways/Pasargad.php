<?php

namespace App\Support\Gateways;

use App\Models\Order;
use Illuminate\Http\Request;

class Pasargad implements GatewayInterface
{

    public function pay(Order $order)
    {
        // TODO: Implement pay() method.
    }

    public function verify(Request $request)
    {
        // TODO: Implement verify() method.
    }

    public function getName(): string
    {
        return "pasargad";
    }
}
