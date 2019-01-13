<template>
    <div class="jumbotron">
        <h1>Welcome</h1>
        <!--<p>bla bla bla bla blabla bla bla bla blabla bla bla bla bla</p>-->
        <app-form></app-form>
    </div>
</template>

<script>
    import form from './Form.vue';
    import home from './home.vue';
    export default {
        data () {
            return {

            }
        },
        components:{
            'app-form':form,
            'app-home':home
        }
    }
</script>

<style scoped>

    .jumbotron{
        background-color: #addfee;
        padding-top: 330px;
        padding-bottom: 50px;
        padding-left: 50px;
        margin:100px 100px 100px 100px;
    }
</style>
