<template>
    <div>
        <table class="table table-striped table-bordered table-responsive table-h3" v-if="title != null ">
            <thead>
            </thead>
            <tbody>

            <tr v-for="(value, key) in rows">
                <td>{{key}}</td>
                <td>{{value}}</td>
            </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['title', 'rows'],
        data() {
            return {}
        }
    }
</script>