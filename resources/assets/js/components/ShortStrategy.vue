<template>
    <div>
        <table class="table table-striped table-bordered table-responsive table-h3" v-if="title != null ">
            <thead>
            <tr>
                <td>次數</td>
                <td>金額</td>
                <td>張數</td>
            </tr>
            </thead>
            <tbody>

            <tr v-for="strategy,index in strategies">
                <td>{{index+1}}</td>
                <td>{{strategy.money}}</td>
                <td>{{strategy.sheet}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['title', 'strategies'],
        data() {
            return {}
        }
    }
</script>