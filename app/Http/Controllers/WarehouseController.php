<?php

namespace App\Http\Controllers;

use App\Enums\WarehouseType;
use App\Formatters\Success;
use App\Http\Requests\Warehouse\AddRequest;
use App\Http\Requests\Warehouse\SoldRequest;
use App\Services\PaginationService;
use App\Services\WarehouseService;
use App\Stock;
use App\Warehouse;
use Illuminate\Support\Carbon;

class WarehouseController extends Controller
{
    public function show()
    {
        return view('warehouse');
    }

    /**
     * 庫存紀錄
     *
     * @param Success $success
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouse(Success $success, WarehouseService $warehouseService, PaginationService $paginationService)
    {
        $userID = auth()->user()->id;

        $warehouses = Warehouse::exist()
            ->where('user_id', $userID)
            ->orderBy('buy_price', 'asc')
            ->orderBy('stock_id', 'asc')
            ->filterStockID(request('stockID'))
            ->with('stock')
            ->paginate(50);

        $stockIDs = $warehouseService->getUserWarehouseStockIDs($userID);
        $this->addWarehouseStockAll($stockIDs);

        $items = [];
        foreach ($warehouses as $warehouse) {
            $items[$warehouse->id] = [
                'id'        => $warehouse->id,
                'name'      => $warehouse->stock->name,
                'stock_id'  => $warehouse->stock_id,
                'buy_price' => $warehouse->buy_price,
                'buy_date'  => $warehouse->buy_date
            ];
        }

        $result = [
            'stockIDs'   => $stockIDs,
            'pagination' => $paginationService->toVuePaginationComponent($warehouses),
            'items'      => $items,
        ];

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }

    /**
     * 將 stock ID 增加 all 選項
     *
     * @param $warehouseStocks
     */
    protected function addWarehouseStockAll(&$stockIDs)
    {
        $stockIDs['all'] = '全部';
    }

    /**
     * 增加庫存
     *
     * @param AddRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(AddRequest $request)
    {
        $userId = auth()->user()->id;
        $warehouses = [];

        //張數
        $sheets = $request->get('sheets');
        $stockID = $request->get('buyStockID');
        $buyPrice = $request->get('buyPrice');
        $buyDate = $request->get('buyDate');

        foreach (range(1, $sheets) as $index) {
            $warehouses[] = [
                'user_id'    => $userId,
                'stock_id'   => $stockID,
                'buy_price'  => $buyPrice,
                'buy_date'   => $buyDate,
                'created_at' => today(),
                'updated_at' => today()
            ];
        }

        Warehouse::insert($warehouses);
        return redirect(route('warehouse.show'));
    }

    /**
     * 出售庫存
     *
     * @param SoldRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sold(SoldRequest $request)
    {
        $warehouseIDs = $request->get('soldModalWarehouseIDs');

        Warehouse::whereIn('id', $warehouseIDs)
            ->update([
                'is_sold'    => 1,
                'sold_price' => $request->get('soldPrice'),
                'sold_date'  => today(),
            ]);

        return redirect(route('warehouse.show'));
    }

    /**
     * 庫存已成交紀錄
     *
     * @param Success $success
     */
    public function dealRecord(Success $success, WarehouseService $warehouseService, PaginationService $paginationService)
    {
        $userID = auth()->user()->id;

        $warehouses = Warehouse::isSold()
            ->where('user_id', $userID)
            ->orderBy('sold_date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->filterStockID(request('stockID'))
            ->with('stock')
            ->paginate(100);

        $stockIDs = $warehouseService->getUserDealWarehouseStockIDs($userID);
        $this->addWarehouseStockAll($stockIDs);

        $items = [];
        foreach ($warehouses as $warehouse) {
            $items[] = [
                'id'         => $warehouse->id,
                'name'       => $warehouse->stock->name,
                'type'       => $warehouse->type,
                'buy_price'  => $warehouse->buy_price,
                'buy_date'   => $warehouse->buy_date,
                'sold_price' => $warehouse->sold_price,
                'sold_date'  => $warehouse->sold_date,
                'loseMoney'  => ($warehouse->sold_price < $warehouse->buy_price + 0.1)
            ];
        }

        $result = [
            'stockIDs'   => $stockIDs,
            'pagination' => $paginationService->toVuePaginationComponent($warehouses),
            'items'      => $items,
        ];

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }
}
