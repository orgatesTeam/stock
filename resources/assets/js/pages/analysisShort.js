Vue.component('e-table', require('../components/Etable.vue'));
Vue.component('short-strategy', require('../components/ShortStrategy.vue'));

window.vm = new Vue({
    el: '#app',
    data: {
        buyPointInfo: {
            title: null,
            rows: null,
        },
        whilePoint: {
            title: null,
            rows: null,
        },
        upPipe: {
            title: null,
            rows: null,
        },
        downPipe: {
            title: null,
            rows: null,
        },
        wavePoint: null,
        msPercent: null,
        money: null,
        waveMoney: null,
        loadingGif: false
    },
    methods: {
        getPipe: function () {
            this.loadingGif = true;
            this.wavePoint = $('#wave-point').val();
            this.msPercent = $('#ms-percent').val();
            this.money = $('#money').val();
            this.waveMoney = $('#wave-money').val();
            let url = $('#ajaxAnalysisUrl').val();
            url  += '?wavePoint=' + this.wavePoint + '&msPercent=' + this.msPercent +
                '&money=' + this.money + "&waveMoney=" + this.waveMoney;
            window.axios.get(url)
                .then((response) => {
                if(response.status === 200
            )
            {
                this.putData(response.data.items);
            }
        })
            ;

            setTimeout(function () {
                vm.loadingGif = false;
            }, 350);
        },
        putData: function (items) {
            this.buyPointInfo = items.buyPointInfo;
            this.whilePoint = items.whilePoint;
            this.upPipe = items.upPipe;
            this.downPipe = items.downPipe;
        }
    }
});


