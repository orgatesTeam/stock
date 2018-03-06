<template>
    <div>
        <h3 class="red-font">股票價格</h3>
        <table class="table table-striped table-bordered table-responsive table-h3">
            <thead>
            <tr>
                <td>名稱</td>
                <td>目前金額</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="warehouse,id in warehouse.stockCurrentPrice">
                <td>{{showStockName(id)}}</td>
                <td>{{warehouse}}</td>
            </tr>

            </tbody>
        </table>

        <h3 class="red-font">庫存量</h3>
        <button type="button" class="btn btn-danger bottom-s btn-lg"
                data-toggle="modal" data-target="#myModal"
                v-show="showSoldButton"
                @click="sold(checkedWarehouses)"
        >賣出</button>
        <table class="table table-striped table-bordered table-responsive table-h3">
            <thead>
                <tr>
                    <td><input type="button" :value="showCheckboxAllName" @click="warehouseCheckboxAll()"></td>
                    <td>名稱</td>
                    <td>買入金額</td>
                    <td>買入日期</td>
                </tr>
            </thead>
            <tbody>

            <tr v-for="warehouse,id in warehouse.items" :class="checkedWarehouses[id] == undefined ? '':'bg-danger'">
                <td><input type="checkbox" name="warehouseCheckbox[]" :value="id" @click="setWarehouses()"></td>
                <td @click="tdChecked(id)">{{warehouse.name}}</td>
                <td @click="tdChecked(id)">{{warehouse.buy_price}}</td>
                <td @click="tdChecked(id)">{{warehouse.buy_date}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['warehouse','sold'],
        data() {
            return {
                check:true,
                checkedWarehouses:{},
            }
        },
        methods:{
            setWarehouses: function(){
                let checkedWarehouses = {};
                let warehouses = this.warehouse.items;
                $('input:checkbox:checked[name="warehouseCheckbox[]"]').each(function(i) {
                    checkedWarehouses[this.value] = warehouses[this.value];
                });
                this.checkedWarehouses = checkedWarehouses;
            },
            warehouseCheckboxAll: function(){
                $("input[name='warehouseCheckbox[]']").prop("checked", this.check);
                this.check = !this.check;
                this.setWarehouses();
            },
            tdChecked: function(id){
                let checked = $("input[value='" + id + "']").prop('checked');
                $("input[value='" + id + "']").prop('checked', !checked);
                this.setWarehouses();
            },
            showStockName: function(stockID){
                return (this.warehouse.stockIDs[stockID]);
            }
        },
        computed:{
            showSoldButton: function(){
                return Object.keys(this.checkedWarehouses).length > 0;
            },
            showCheckboxAllName: function(){
                return this.check ? '全選':'反選';
            }
        }
    }
</script>