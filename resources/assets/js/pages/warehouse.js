require('../bootstrap');
require('jquery.cookie')

Vue.component('warehouse-table', require('../components/WarehouseTable.vue'));
Vue.component('warehouse-deal-table', require('../components/WarehouseDealTable.vue'));
Vue.component('pagination', require('../components/Pagination.vue'));

window.vm = new Vue({
    el: '#app',
    data: {
        warehouse:{
            'types':{},
            'currentPage':1,
            'currentType':'',
            'items':{},
            'pagination':''
        },
        dealWarehouse:{
            'types':{},
            'currentPage':1,
            'currentType':'',
            'items':{},
            'pagination':''
        },
        soldWarehouses:{}

    },
    mounted: function () {
        this.selectedWarehouseType = this.cookieWarehouseType;
        this.selectedDealWarehouseType = this.cookieDealWarehouseType;
    },
    methods: {
        getWarehousePage: function(page){
            this.warehouse.currentPage = page;
            this.setWarehouseItems();
        },
        getDealWarehousePage: function(page){
            this.dealWarehouse.currentPage = page;
            this.setDealWarehouseItems();
        },
        warehouseSoldModal: function(soldWarehouses){
            this.soldWarehouses = soldWarehouses;
        },
        setWarehouseItems: function(){
            let url = this.warehouseUrl;
            this.warehouses = {};
            window.axios.get(url)
                .then((response) => {
                if(response.status === 200){
                items = response.data.items;
                this.warehouse.types = items.types;
                this.warehouse.items = items.items;
                this.warehouse.pagination = items.pagination;
            }});
        },
        setDealWarehouseItems: function(){
            let url = this.dealWarehouseUrl;
            this.warehouses = {};
            window.axios.get(url)
                .then((response) => {
                if(response.status === 200){
                items = response.data.items;
                this.dealWarehouse.types = items.types;
                this.dealWarehouse.items = items.items;
                this.dealWarehouse.pagination = items.pagination;
            }});
        },
    },
    computed: {
        buyType: {
            get: function (){
                return $.cookie("buyType");
            },
            set: function(type){
                $.cookie("buyType", type);
            }
        },
        cookieWarehouseType: {
            get: function (){
                return $.cookie("warehouseType");
            },
            set: function(type){
                $.cookie("warehouseType", type);
            }
        },
        cookieDealWarehouseType: {
            get: function (){
                return $.cookie("dealWarehouseType");
            },
            set: function(type){
                $.cookie("dealWarehouseType", type);
            }
        },
        warehouseUrl: function (){
            let page = 'page='+ this.warehouse.currentPage;
            let type = 'type='+ this.selectedWarehouseType;
            return $('#ajaxWarehouseRecordUrl').val()+'?'+page+'&'+type;
        },
        dealWarehouseUrl: function (){
            let page = 'page='+ this.dealWarehouse.currentPage;
            let type = 'type='+ this.selectedDealWarehouseType;
            return $('#ajaxWarehouseDealRecordUrl').val()+'?'+page+'&'+type;
        },
        selectedWarehouseType: {
            get: function () {
                return (this.warehouse.currentType === undefined) ? 'all' : this.warehouse.currentType;
            },
            set: function (type) {
                this.warehouse.currentPage = 1;
                this.warehouse.currentType = type;
                this.cookieWarehouseType = type;
                this.setWarehouseItems();
            }
        },
        selectedDealWarehouseType: {
            get: function () {
                return (this.dealWarehouse.currentType === undefined) ? 'all' : this.dealWarehouse.currentType;
            },
            set: function (type) {
                this.dealWarehouse.currentPage = 1;
                this.dealWarehouse.currentType = type;
                this.cookieDealWarehouseType = type;
                this.setDealWarehouseItems();
            }
        }
    }
});


