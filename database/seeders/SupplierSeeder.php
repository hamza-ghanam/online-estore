<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::truncate();

        $suppliers = [
            [
                'companyName' => 'SwafTech',
                'contactName' => 'Ahmad',
                'city' => 'Damascus',
                'country' => 'Syria',
                'phone' => '33324587',
                'fax' => '33324588',
            ],
            [
                'companyName' => 'Durra',
                'contactName' => 'سعيد',
                'city' => 'دمشق',
                'country' => 'سوريا',
                'phone' => '0113855454',
                'fax' => null,
            ],
            [
                'companyName' => 'كهربائيات المصري',
                'contactName' => 'محمود',
                'city' => 'حلب',
                'country' => 'سوريا',
                'phone' => null,
                'fax' => null,
            ],
        ];

        // Use this method for created_at and updated_at take values, not NULL
        foreach ($suppliers as $cc) {
            Supplier::create($cc);
        }


//        Supplier::insert($suppliers);

    }
}
