<template>
    <div>
        <table class="table table-striped table-bordered table-responsive table-h3">
            <thead>
                <tr>
                    <td>名稱</td>
                    <td>買入金額</td>
                    <td>賣出金額</td>
                    <td>買入日期</td>
                    <td>賣出日期</td>
                </tr>
            </thead>
            <tbody>

            <tr v-for="record,index in dealRecord" :class="record.loseMoney?'bg-danger':''">
                <td>{{record.name}}</td>
                <td>{{record.buy_price}}</td>
                <td>{{record.sold_price}}</td>
                <td>{{record.buy_date}}</td>
                <td>{{record.sold_date}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['dealRecord'],
        data() {
            return {
            }
        }
    }

</script>