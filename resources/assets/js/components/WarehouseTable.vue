<template>
    <div>
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

            <tr v-for="warehouse,id in warehouses" :class="checkedWarehouses[id] == undefined ? '':'bg-danger'">
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
        props: ['warehouses','sold'],
        data() {
            return {
                check:true,
                checkedWarehouses:{},
            }
        },
        methods:{
            setWarehouses: function(){
                let checkedWarehouses = {};
                let warehouses = this.warehouses;
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
            }
        },
        computed:{
            showSoldButton: function(){
            console.log(this.checkedWarehouses);
                return Object.keys(this.checkedWarehouses).length > 0;
            },
            showCheckboxAllName: function(){
                return this.check ? '全選':'反選';
            }
        }
    }
</script>