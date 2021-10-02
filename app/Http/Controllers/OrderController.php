<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::orderBy('orderDate', 'DESC')->with('customer')->paginate(5);

        if ($orders) {
            return MessagesController::responder(200, $orders);
        }

        return MessagesController::responder(-500, [], 'Cannot fetch orders.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $order = Order::find($id)->load('products');

        if ($order) {
            return MessagesController::responder(200, $order);
        }

        return MessagesController::responder(404, [], 'No such order.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required|integer',
            'orderItems' => 'required|array',
            'orderItems.*.product_id' => 'required|integer',
            'orderItems.*.quantity' => 'required|integer|gt:0',
            'orderItems.*.unitPrice' => 'required|numeric|gt:0',
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'integer' => 'The :attribute field should be integer.',
            'array' => 'The :attribute field should be array.',
            'numeric' => 'The :attribute field should be numeric.',
            'gt:0' => 'The :attribute field should be positive.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return MessagesController::responder(400, $validator->errors(), 'Some invalid fields.');
        }

        if (!Customer::find($request->customer_id)) {
            return MessagesController::responder(404, [], 'No such customer.');
        }

        $newOrder = new Order;
        $newOrder->orderDate = $request->orderDate ? $request->orderDate : Carbon::now();

        $terms = explode('-', Str::uuid()->toString());
        $newOrder->orderNumber = substr($terms[0], 0, 5)
            . substr($terms[1], 0, 2)
            . substr($terms[2], -1)
            . substr($terms[1], 0, 2);

        $newOrder->customer_id = $request->customer_id;

        $orderItems = $request->orderItems;
        $totalAmount = 0;

        foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem['product_id']);

            if (!$product) {
                return MessagesController::responder(404, $validator->errors(), 'Some products are not found.');
            }

            $totalAmount += $orderItem['quantity'] * $orderItem['unitPrice'];
        }

        $newOrder->totalAmount = $totalAmount;

        $saved = $newOrder->save();

        if (!$saved) {
            return MessagesController::responder(500, [], 'Cannot delete product.');
        }

        $newOrder->products()->attach($orderItems);

        return MessagesController::responder(201, $newOrder->load('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'customer_id' => 'required|integer',
            'orderItems' => 'required|array'
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'integer' => 'The :attribute field should be integer.',
            'array' => 'The :attribute field should be array.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return MessagesController::responder(400, $validator->errors(), 'Some invalid fields.');
        }

        $existingOrder = Order::find($id);

        if (!$existingOrder) {
            return MessagesController::responder(404, [], 'No such order.');
        }

        $orderItems = $request->orderItems;

        $totalAmount = 0;

        foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem['product_id']);

            if (!$product) {
                return MessagesController::responder(404, $validator->errors(), 'Some products are not found.');
            }

            $totalAmount += $orderItem['quantity'] * $orderItem['unitPrice'];
        }

        $existingOrder->customer_id = $request->customer_id;

        $existingOrder->products()->detach();

        $existingOrder->products()->attach($orderItems);

        $existingOrder->totalAmount = $totalAmount;

        $saved = $existingOrder->save();

        if (!$saved) {
            return MessagesController::responder(500, [], 'Cannot delete product.');
        }

        return MessagesController::responder(200, $existingOrder->load('products'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingOrder = Order::find($id)->load('products');

        if (!$existingOrder) {
            return MessagesController::responder(404, [], 'No such order.');
        }

        foreach ($existingOrder->products as $product) {
            $existingOrder->products()->updateExistingPivot($product->id, ['deleted_at' => Carbon::now()], false);
        }

        $deleted = $existingOrder->delete();

        if ($deleted) {
            return MessagesController::responder(200, [], 'Order deleted.');
        }

        return MessagesController::responder(500, [], 'Cannot delete order.');

    }
}
