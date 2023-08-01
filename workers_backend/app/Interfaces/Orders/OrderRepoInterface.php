<?php

namespace App\Interfaces\Orders;

interface OrderRepoInterface
{
    public function store($request);
    public function getClientOrders();
}
