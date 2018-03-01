<template>
    <div>
        <ul class="pagination" :class="pagination.hasPages">
            <li v-for="index in pagination.lastPage" :class="index==pagination.currentPage?'disabled':'pointer'">
                <a v-on:click="getPage(index)" rel="prev">{{index}}</a>
            </li>
            <li class="disabled">
                <span>目前第 {{pagination.currentPage}} 頁，總共 {{pagination.total?pagination.total:0}} 筆數</span>
            </li>
        </ul>

    </div>
</template>
<script>
    export default {
        props: ['pagination','getPage'],
        data() {
            return {}
        }
    }
</script>