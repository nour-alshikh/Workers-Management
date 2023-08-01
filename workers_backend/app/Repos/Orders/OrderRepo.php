<?php

namespace App\Repos\Orders;

use App\Models\Order;
use App\Interfaces\Orders\OrderRepoInterface;

class OrderRepo implements OrderRepoInterface
{
    public function store($request)
    {
        $clientId = auth()->guard('clients')->id();
        if (Order::where('client_id', '=', $clientId)->where('post_id', '=', $request->post_id)->exists()) {
            return response()->json([
                'message' => 'Order already submitted',
            ]);
        }
        $data = $request->all();
        $data['client_id'] = auth()->guard('clients')->id();

        $order = Order::create($data);
        return response()->json([
            'message' => 'order Added successfully',
            'order' => $order
        ]);
    }
    public function getClientOrders()
    {
        $orders = Order::whereStatus('pending')->whereHas('post', function ($query) {
            $query->where('worker_id', '=', auth()->guard('workers')->id());
        })->with('post:id,content', 'client:id,name')->get();
        return response()->json([
            'orders' => $orders
        ]);
    }
}
