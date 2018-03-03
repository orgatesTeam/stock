<?php

use Illuminate\Database\Seeder;
use App\Stock;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = [
            [
               'no'=>'00627L',
                'name'=>'元大S&P原油正2'
            ],
            [
                'no'=>'00637L',
                'name'=>'元大滬深300正2'
            ]
        ];

        Stock::insert($stocks);
    }
}
