<?php

namespace App\Presenters;

use Orbas\Util\Presenter;

class Warehouse extends Presenter
{
    public function typeChineseName()
    {
        $type = $this->attribute('type');
        return  config('warehouse.name.'.$type);
    }
}