<?php
use \App\Enums\WarehouseType;

return [
    WarehouseType::全部   => '全部',
    WarehouseType::石油正二 => '元大S&P原油正二' . '(' . WarehouseType::石油正二 . ')',
    WarehouseType::滬深正二 => '元大滬深300正二' . '(' . WarehouseType::石油正二 . ')'
];