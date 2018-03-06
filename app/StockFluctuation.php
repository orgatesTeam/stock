<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockFluctuation extends Model
{
    protected $fillable = ['stock_id', 'closing_price', 'created_at'];
}
