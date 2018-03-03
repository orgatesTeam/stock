<?php

namespace App\Services;

use App\Enums\WarehouseType;
use Yish\Generators\Foundation\Service\Service;
use App\Warehouse;
use Illuminate\Database\Eloquent\Builder;

class WarehouseService extends Service
{
    protected $repository;

    public function getUserWarehouseStockIDs($userID)
    {
        $warehouses = Warehouse::exist()
            ->where('user_id', $userID)
            ->groupBy('stock_id')
            ->select('stock_id')
            ->with('stock')
            ->get();

        $stockIDs = [];
        foreach ($warehouses as $warehouse) {
            $stockIDs[$warehouse->stock_id] = $warehouse->stock->name;
        }

        return $stockIDs;
    }

    public function getUserDealWarehouseStockIDs($userID)
    {
        $warehouses = Warehouse::isSold()
            ->where('user_id', $userID)
            ->groupBy('stock_id')
            ->select('stock_id')
            ->with('stock')
            ->get();

        foreach ($warehouses as $warehouse) {
            $stockIDs[$warehouse->stock_id] = $warehouse->stock->name;
        }

        return $stockIDs;
    }
}
