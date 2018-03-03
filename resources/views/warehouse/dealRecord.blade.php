<div id="record" class="tab-pane fade">

    <div>
        <select id="dealStockID" v-model="selectedDealWarehouseStockID" v-show="dealWarehouse.stockIDs">
            <option v-for="value,key in dealWarehouse.stockIDs" :value="key">@{{ value }}</option>
        </select>
    </div>

    <div>
        <pagination :pagination="dealWarehouse.pagination" :get-page="getDealWarehousePage"></pagination>
    </div>
    <warehouse-deal-table :deal-record="dealWarehouse.items"></warehouse-deal-table>

    <input type="hidden" id="ajaxWarehouseDealRecordUrl" value="{{route('warehouse.deal.record')}}">
</div>