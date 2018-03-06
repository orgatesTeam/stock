<?php

namespace App\Console\Commands;

use App\Console\ConsoleLogTrait;
use App\Stock;
use App\StockFluctuation;
use Illuminate\Console\Command;

class PullStockClosingPrice extends Command
{
    use ConsoleLogTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:stock:closingPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取股價';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stocks = Stock::all();

        //PS = pull stock
        foreach ($stocks as $stock) {

            $html = file_get_contents($this->url($stock->no));

            $rule = '/<td[^>]*class="price rt ft16bold r"*>(.*?)<\/td>/si';
            $return = preg_match_all($rule, $html, $matches, PREG_OFFSET_CAPTURE);
            if ($return) {
                $this->logger('PullStockClosingPrice')->info($stock->no, ['matches' => $matches]);
                $closingPrice = $matches[1][0][0];
                $stockFluctuation = new StockFluctuation([
                    'stock_id'      => $stock->id,
                    'closing_price' => $closingPrice
                ]);

                $stockFluctuation->save();
                $this->logger('PullStockClosingPrice')->info($stock->no, ['stock_fluctuation_id' => $stockFluctuation->id]);
            } else {
                $this->logger('PullStockClosingPrice')->info($stock->no, ['matches' => null]);
            }
        }
    }

    public function url($stockNo)
    {
        return "http://traderoom.cnyes.com/tse/quote2FB.aspx?code=$stockNo";
    }
}
