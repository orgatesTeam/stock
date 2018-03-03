<div id="warehouse" class="tab-pane fade  in active">

    <div>
        <select id="warehouseType" v-model="selectedWarehouseType" v-show="warehouse.types">
            <option v-for="key,value in warehouse.types" :value="value">@{{ key }}</option>
        </select>
    </div>

    <div>
        <pagination :pagination="warehouse.pagination" :get-page="getWarehousePage"></pagination>
    </div>
    <warehouse-table :warehouses="warehouse.items" :sold="warehouseSoldModal"></warehouse-table>

    <input type="hidden" id="ajaxWarehouseRecordUrl" value="{{route('warehouse.record')}}">

</div>