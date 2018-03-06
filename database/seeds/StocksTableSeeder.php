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
        Stock::insert($this->stocks());
        $stocks = Stock::all();
        $this->makeConfig($stocks);
    }

    protected function makeConfig($stocks)
    {
        $filePath = base_path() . '/config/stocks.php';

        if (is_file($filePath)) {
            unlink($filePath);
        }

        $output = '';
        foreach ($stocks as $stock) {
            $output .= " '$stock->id' => ['no'=>'$stock->no' , 'name' => '$stock->name'] ,";
        }
        $output = str_replace("output", $output, "<?php return [output];");

        $file = fopen($filePath, "a+");
        fwrite($file, $output);
    }

    protected function stocks()
    {
        return [
            [
                'no'   => '00672L',
                'name' => '元大S&P原油正2'
            ],
            [
                'no'   => '00637L',
                'name' => '元大滬深300正2'
            ]
        ];
    }
}
