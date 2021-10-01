<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();

        $products = [
            [
                'productName' => 'Chai',
                'supplier_id' => 1,
                'unitPrice' => 150
            ],
            [
                'productName' => 'Rice',
                'supplier_id' => 1,
                'unitPrice' => 300
            ],
            [
                'productName' => 'Sugar',
                'supplier_id' => 2,
                'unitPrice' => 250
            ],
            [
                'productName' => 'Biscuit',
                'supplier_id' => 3,
                'unitPrice' => 500
            ],
        ];

        // Use this method for created_at and updated_at take values, not NULL
        foreach ($products as $cc) {
            Product::create($cc);
        }

       // Product::insert($products);

    }
}
