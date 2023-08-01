<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\OrderRequest;
use App\Interfaces\Orders\OrderRepoInterface;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderRepo;
    function __construct(OrderRepoInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
    function addOrder(OrderRequest $request)
    {
        return $this->orderRepo->store($request);
    }
    function getClientOrders()
    {
        return $this->orderRepo->getClientOrders();
    }
    function changeStatus($id, Request $request)
    {
        $order = Order::find($id);
        $order->setAttribute('status', $request->status)->save();
        return response()->json([
            'message' => 'status updated'
        ]);
    }
}
