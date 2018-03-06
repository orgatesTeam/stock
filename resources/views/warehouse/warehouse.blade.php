<div id="warehouse" class="tab-pane fade  in active">

    <div>
        <select id="warehouseStockID" v-model="selectedWarehouseStockID" v-show="warehouse.stockIDs">
            <option v-for="value,key in warehouse.stockIDs" :value="key">@{{ value }}</option>
        </select>
    </div>

    <div>
        <pagination :pagination="warehouse.pagination" :get-page="getWarehousePage"></pagination>
    </div>
    <warehouse-table :warehouse="warehouse" :sold="warehouseSoldModal"></warehouse-table>

    <input type="hidden" id="ajaxWarehouseRecordUrl" value="{{route('warehouse.record')}}">

</div>