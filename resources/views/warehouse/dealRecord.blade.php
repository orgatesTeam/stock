<div id="record" class="tab-pane fade">

    <div>
        <select id="dealType" v-model="selectedDealWarehouseType">
            <option v-for="key,value in dealWarehouse.types" v-bind:value="value">@{{ key }}</option>
        </select>
    </div>

    <div>
        <pagination :pagination="dealWarehouse.pagination" :get-page="getDealWarehousePage"></pagination>
    </div>
    <warehouse-deal-table :deal-record="dealWarehouse.items"></warehouse-deal-table>

    <input type="hidden" id="ajaxWarehouseDealRecordUrl" value="{{route('warehouse.deal.record')}}">
</div>