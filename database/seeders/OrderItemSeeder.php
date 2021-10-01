<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderItems = [
            1 => [
                [
                    'order_id' => 1,
                    'product_id' => 4,
                    'unitPrice' => 500,
                    'quantity' => 2
                ]
            ],
            2 => [
                [
                    'order_id' => 2,
                    'product_id' => 3,
                    'unitPrice' => 300,
                    'quantity' => 1
                ],
                [
                    'order_id' => 2,
                    'product_id' => 2,
                    'unitPrice' => 300,
                    'quantity' => 1
                ]
            ]
        ];

        $orders = [1, 2];

        foreach ($orders as $orderId) {
            $order = Order::findOrFail($orderId);

            $order->products()->detach();

            $order->products()->attach($orderItems[$orderId]);
        }
    }
}
