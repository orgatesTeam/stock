<?php

namespace App\Http\Controllers;

use App\Enums\WarehouseType;
use App\Formatters\Success;
use App\Http\Requests\Warehouse\AddRequest;
use App\Http\Requests\Warehouse\SoldRequest;
use App\Services\PaginationService;
use App\Services\WarehouseService;
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
            ->orderBy('type', 'asc')
            ->filterType(request('type'))
            ->paginate(50);

        $items = [];
        $warehouseTypes = $warehouseService->getUserWarehouseTypes($userID);
        $this->addWarehouseTypeAll($warehouseTypes);

        foreach ($warehouses as $warehouse) {
            $items[$warehouse->id] = [
                'id'        => $warehouse->id,
                'name'      => $warehouse->present()->typeChineseName(),
                'type'      => $warehouse->type,
                'buy_price' => $warehouse->buy_price,
                'buy_date'  => $warehouse->buy_date
            ];
        }

        $result = [
            'pagination' => $paginationService->toVuePaginationComponent($warehouses),
            'items'      => $items,
            'types'      => $warehouseTypes,
        ];

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }

    /**
     * 將 types 增加 all type 選項
     *
     * @param $warehouseTypes
     */
    protected function addWarehouseTypeAll(&$warehouseTypes)
    {
        $warehouseTypeAll = new WarehouseType(WarehouseType::全部);
        $warehouseTypes[$warehouseTypeAll->getValue()] = $warehouseTypeAll->getKey();
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
        $type = $request->get('type');
        $buyPrice = $request->get('buyPrice');
        $buyDate = $request->get('buyDate');

        foreach (range(1, $sheets) as $index) {
            $warehouses[] = [
                'user_id'    => $userId,
                'type'       => $type,
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
            ->filterType(request('type'))
            ->paginate(100);

        $warehouseTypes = $warehouseService->getUserDealWarehouseTypes($userID);
        $this->addWarehouseTypeAll($warehouseTypes);

        $items = [];
        foreach ($warehouses as $warehouse) {
            $items[] = [
                'id'         => $warehouse->id,
                'name'       => $warehouse->present()->typeChineseName(),
                'type'       => $warehouse->type,
                'buy_price'  => $warehouse->buy_price,
                'buy_date'   => $warehouse->buy_date,
                'sold_price' => $warehouse->sold_price,
                'sold_date'  => $warehouse->sold_date,
                'loseMoney'  => ($warehouse->sold_price < $warehouse->buy_price + 0.1)
            ];
        }

        $result = [
            'pagination' => $paginationService->toVuePaginationComponent($warehouses),
            'items'      => $items,
            'types'      => $warehouseTypes,
        ];

        return response()->json(
            $success->format(
                request(),
                $result
            )
        );
    }
}
