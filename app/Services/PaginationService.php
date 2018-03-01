<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    /**
     * vue pagination component 所需要 物件
     */
    public function toVuePaginationComponent(LengthAwarePaginator $paginator)
    {
        return [
            'total'           => $paginator->total(),
            'currentPage'     => $paginator->currentPage(),
            'lastPage'        => $paginator->lastPage(),
            'hasPages'        => $paginator->hasPages(),
        ];
    }
}
