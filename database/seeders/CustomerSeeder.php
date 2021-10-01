<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::truncate();

        $customers = [
            [
                'firstName' => 'Mohamad',
                'lastName' => 'Zid',
                'city' => 'Beirut',
                'country' => 'Lebanon',
                'phone' => '02015485546',
            ],
            [
                'firstName' => 'Samer',
                'lastName' => 'Salam',
                'city' => 'Damascus',
                'country' => 'Syria',
                'phone' => '555456687',
            ]
        ];


        // Use this method for created_at and updated_at take values, not NULL
        foreach ($customers as $cc) {
            Customer::create($cc);
        }


        //Customer::insert($customers);
    }
}
