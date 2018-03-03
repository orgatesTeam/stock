<?php

namespace App;

use App\Enums\WarehouseType;
use Illuminate\Database\Eloquent\Model;
use Orbas\Util\Traits\Presenter;

class Warehouse extends Model
{
    use Presenter;

    protected $fillable = ['user_id', 'stock_id', 'is_sold', 'buy_date', 'buy_price', 'sold_date', 'sold_price'];

    public function scopeExist($query)
    {
        return $query->where('is_sold', 0);
    }

    public function scopeIsSold($query)
    {
        return $query->where('is_sold', 1);
    }

    public function scopeFilterStockID(&$query, $stockID)
    {
        if ($stockID > 0) {
            $query->where('stock_id', $stockID);
        }
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
