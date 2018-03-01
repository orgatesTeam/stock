<?php

namespace App;

use App\Enums\WarehouseType;
use Illuminate\Database\Eloquent\Model;
use Orbas\Util\Traits\Presenter;

class Warehouse extends Model
{
    use Presenter;

    protected $fillable = ['user_id', 'type', 'is_sold', 'buy_date', 'buy_price', 'sold_date', 'sold_price'];

    public function scopeExist($query)
    {
        return $query->where('is_sold', 0);
    }

    public function scopeIsSold($query)
    {
        return $query->where('is_sold', 1);
    }

    public function scopeFilterType($query, $type)
    {
        if ($type == WarehouseType::全部 || !$type) {
            return $query;
        }

        return $query->where('type', $type);
    }
}
