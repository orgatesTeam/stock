require('../bootstrap');
require('jquery.cookie')

Vue.component('warehouse-table', require('../components/WarehouseTable.vue'));
Vue.component('warehouse-deal-table', require('../components/WarehouseDealTable.vue'));
Vue.component('pagination', require('../components/Pagination.vue'));

window.vm = new Vue({
    el: '#app',
    data: {
        warehouse:{
            'stockIDs':{},
            'currentPage':1,
            'currentStockID':'',
            'items':{},
            'pagination':'',
            'stockCurrentPrice':{}
        },
        dealWarehouse:{
            'stockIDs':{},
            'currentPage':1,
            'currentStockID':'',
            'items':{},
            'pagination':''
        },
        soldWarehouses:{}
    },
    mounted: function () {
        this.selectedWarehouseStockID = this.cookieWarehouseStockID;
        this.selectedDealWarehouseStockID = this.cookieDealWarehouseStockID;
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
                console.log(items.items);
                this.warehouse.stockIDs = items.stockIDs;
                this.warehouse.items = items.items;
                this.warehouse.pagination = items.pagination;
                this.warehouse.stockCurrentPrice = items.stockCurrentPrice
            }});
        },
        setDealWarehouseItems: function(){
            let url = this.dealWarehouseUrl;
            this.warehouses = {};
            window.axios.get(url)
                .then((response) => {
                if(response.status === 200){
                items = response.data.items;
                this.dealWarehouse.stockIDs = items.stockIDs;
                this.dealWarehouse.items = items.items;
                this.dealWarehouse.pagination = items.pagination;
            }});
        },
    },
    computed: {
        buyStockID: {
            get: function (){
                return $.cookie("buyStockID");
            },
            set: function($stockID){
                $.cookie("buyStockID", $stockID);
            }
        },
        cookieWarehouseStockID: {
            get: function (){
                return $.cookie("warehouseStockID");
            },
            set: function(stockID){
                $.cookie("warehouseStockID", stockID);
            }
        },
        cookieDealWarehouseStockID: {
            get: function (){
                return $.cookie("dealWarehouseStockID");
            },
            set: function(stockID){
                $.cookie("dealWarehouseStockID", stockID);
            }
        },
        warehouseUrl: function (){
            let page = 'page='+ this.warehouse.currentPage;
            let stockID = 'stockID='+ this.selectedWarehouseStockID;
            return $('#ajaxWarehouseRecordUrl').val()+'?'+page+'&'+stockID;
        },
        dealWarehouseUrl: function (){
            let page = 'page='+ this.dealWarehouse.currentPage;
            let stockID = 'stockID='+ this.selectedDealWarehouseStockID;
            return $('#ajaxWarehouseDealRecordUrl').val()+'?'+page+'&'+stockID;
        },
        selectedWarehouseStockID: {
            get: function () {
                return (this.warehouse.currentStockID === undefined) ? 'all' : this.warehouse.currentStockID;
            },
            set: function (stockID) {
                this.warehouse.currentPage = 1;
                this.warehouse.currentStockID = stockID;
                this.cookieWarehouseStockID = stockID;
                this.setWarehouseItems();
            }
        },
        selectedDealWarehouseStockID: {
            get: function () {
                return (this.dealWarehouse.currentStockID === undefined) ? 'all' : this.dealWarehouse.currentStockID;
            },
            set: function (stockID) {
                this.dealWarehouse.currentPage = 1;
                this.dealWarehouse.currentStockID = stockID;
                this.cookieDealWarehouseStockID = stockID;
                this.setDealWarehouseItems();
            }
        }
    }
});


