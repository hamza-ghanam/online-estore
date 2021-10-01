<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();

        $orders = [
            [
                'orderDate' => '2020-07-05',
                'orderNumber' => '5',
                'customer_id' => 1,
                'totalAmount' => 1000
            ],
            [
                'orderDate' => '2020-08-14',
                'orderNumber' => '8',
                'customer_id' => 2,
                'totalAmount' => 600
            ]
        ];

        // Use this method for created_at and updated_at take values, not NULL
        foreach ($orders as $cc) {
            Order::create($cc);
        }

        // Order::insert($orders);

    }
}
