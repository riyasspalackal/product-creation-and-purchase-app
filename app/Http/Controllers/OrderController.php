<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function createOrder(Request $request)
    {

        $user = Auth::id();
        $order = new Order;
        $order->order_no = strtoupper(uniqid('O' . '-'));
        $order->order_date = Carbon::now()->format('Y-m-d H:i:s');
        $order->product_id = $request->get('product_id');
        $order->user_id = $user;
        $order->price = $request->get('price');

        $order->save();

        return response()
            ->json([
                'order' => $order,
            ], Response::HTTP_CREATED);
    }

    public function getOrderByUser()
    {
        $user = Auth::id();
        $orderList = Order::with(['product', 'payments'])->withCount(['payments as paid_amount' => function ($q) {
            $q->select(DB::raw("SUM(order_transactions.amount)"));
        }])->where('user_id', $user)->get();
        return response()->json(['status' => true, 'message' => 'Success', 'orders' => $orderList], Response::HTTP_OK);
    }

    public function getOrders()
    {
        $user = Auth::id();
        $orderList = Order::with(['product', 'payments', 'user'])->withCount(['payments as paid_amount' => function ($q) {
            $q->select(DB::raw("SUM(order_transactions.amount)"));
        }])->get();
        return response()->json(['status' => true, 'message' => 'Success', 'orders' => $orderList], Response::HTTP_OK);
    }

    public function pay(Request $request)
    {

        $orderTransaction = new OrderTransaction;
        $orderTransaction->transaction_id = strtoupper(uniqid());
        $orderTransaction->order_id = $request->get('order_id');
        $orderTransaction->amount = $request->get('amount');
        $orderTransaction->payment_type = $request->get('payment_type');
        $orderTransaction->save();

        return response()
            ->json([
                'message' => 'Success',
            ], Response::HTTP_CREATED);
    }

    public function getTransactionHistory($orderId, Request $request)
    {

        $orderTransitions = OrderTransaction::where('order_id', $orderId);
        return $this->paginate($request, $orderTransitions);
    }
}
