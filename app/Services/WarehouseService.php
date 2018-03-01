<?php

namespace App\Services;

use App\Enums\WarehouseType;
use Yish\Generators\Foundation\Service\Service;
use App\Warehouse;
use Illuminate\Database\Eloquent\Builder;

class WarehouseService extends Service
{
    protected $repository;

    public function getUserWarehouseTypes($userID)
    {
        $warehouses = Warehouse::exist()
            ->where('user_id', $userID)
            ->groupBy('type')
            ->select('type')
            ->get();

        foreach ($warehouses as $warehouse) {
            $types[$warehouse->type] = $warehouse->present()->typeChineseName();
        }

        return $types;
    }

    public function getUserDealWarehouseTypes($userID)
    {
        $warehouses = Warehouse::isSold()
            ->where('user_id', $userID)
            ->groupBy('type')
            ->select('type')
            ->get();

        foreach ($warehouses as $warehouse) {
            $types[$warehouse->type] = $warehouse->present()->typeChineseName();
        }

        return $types;
    }
}
