<?php

namespace App\Presenters;

use App\Enums\WarehouseType;
use Orbas\Util\Presenter;

class Warehouse extends Presenter
{
    public function typeChineseName()
    {
        $type = $this->attribute('type');
        $warehouseType = new WarehouseType($type);
        return $warehouseType->getKey();
    }
}