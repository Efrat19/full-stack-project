<template>
    <div id="app">
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
                  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
                  crossorigin="anonymous">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
                  integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
                  crossorigin="anonymous">
        </head>
        <body>
        <ul class="nav justify-content-center">
            <li class="nav-item">
                <router-link to="/" class="nav-link active">Home</router-link>
            </li>
            <li class="nav-item">
                <router-link to="/inside" class="nav-link">View Users</router-link>
            </li>
            <li class="nav-item">
                <a id="up" href="#" v-show="!acceptTerms" class="nav-link" @click="signupout('up')">Sign Up</a>
            </li>
            <li class="nav-item">
                <a id="out" v-show="acceptTerms" class="nav-link" href="#" @click="signupout('out')">Sign Out</a>
            </li>
        </ul>
        <router-view></router-view>
        </body>
    </div>
</template>

<script>
    import {router} from './main.js';
    export default {
        data: function () {
            return {
//                signupclass:,
//                signoutclass: 'nav-link',
//                getClass: ''
                signed: false
            }
        },
        computed: {
            status: {
                get(){
                    return this.$store.state.status
                }, set(pl){
                    this.$store.dispatch('setStatus', pl)
                }
            },
            name: {
                get(){
                    return this.$store.state.name
                }, set(pl){
                    this.$store.dispatch('setName', pl)
                }
            },
            email: {
                get(){
                    return this.$store.state.email
                }, set(pl){
                    this.$store.dispatch('setEmail', pl)
                }
            },
            phone: {
                get(){
                    return this.$store.state.phone
                }, set(pl){
                    this.$store.dispatch('setPhone', pl)
                }
            },
            dateOfBirth: {
                get(){
                    return this.$store.state.dateOfBirth
                }, set(pl){
                    this.$store.dispatch('setBirth', pl)
                }
            },
            acceptTerms: {
                get(){
                    return this.$store.state.acceptTerms
                }, set(pl){
                    this.$store.dispatch('setTerms', pl)
                }
            },
            acceptMailing: {
                get(){
                    return this.$store.state.acceptMailing
                }, set(pl){
                    this.$store.dispatch('setMailing', pl)
                }
            },
        },
//        watch: {
//            'getClass': function () {
//                console.log('getclass');
//                if (where == 'up') {
//                    if (this.email == '')
//                        return 'nav-link';
//                    return 'nav-link disabled';
//                }
//                if (where == 'out') {
//                    if (this.email != '')
//                        return 'nav-link';
//                    return 'nav-link disabled';
//                }
//            },
//        },
//        watch:{
//            'status':function () {
//                localStorage.setItem('status', JSON.stringify(this.status));
//            },
//            'name':function () {
//                localStorage.setItem('name', JSON.stringify(this.name));
//            },
//            'email':function () {
//                localStorage.setItem('email', JSON.stringify(this.email));
//            },
//            'phone':function () {
//                localStorage.setItem('phone', JSON.stringify(this.phone));
//            },
//            'dateOfBirth':function () {
//                localStorage.setItem('dateOfBirth', JSON.stringify(this.dateOfBirth));
//            },
//            'acceptTerms':function () {
//                localStorage.setItem('acceptTerms', JSON.stringify(this.acceptTerms));
//            },
//            'acceptMailing':function () {
//                localStorage.setItem('acceptMailing', JSON.stringify(this.acceptMailing));
//            },
//        },
        methods: {
            signupout: function (where) {
                this.status = "Sign Up";
                this.name = "";
                this.phone = "";
                this.email = "";
                this.dateOfBirth = undefined;
                this.acceptTerms = false;
                this.acceptMailing = true;
                localStorage.clear();
//                localStorage.setItem('status', JSON.stringify(this.status));
//                localStorage.setItem('name', JSON.stringify(this.name));
//                localStorage.setItem('email', JSON.stringify(this.email));
//                localStorage.setItem('phone', JSON.stringify(this.phone));
//                localStorage.setItem('dateOfBirth', JSON.stringify(this.dateOfBirth));
//                localStorage.setItem('acceptTerms', JSON.stringify(this.acceptTerms));
//                localStorage.setItem('acceptMailing', JSON.stringify(this.acceptMailing));
                if (where == 'out') {
                    router.push('/');
                }
                if (where == 'up')
                    router.push('/form');
// if (where == 'out'&&document.getElementById(where).class=='nav-link') {
//                    document.getElementById(where).class='nav-link disabled';
//                    document.getElementById('up').class='nav-link';
//                    router.push('/');
//                }
//                if (where == 'up'&&document.getElementById(where).class=='nav-link')
//                    document.getElementById(where).clss='nav-link disabled';
//                document.getElementById('out').class='nav-link';
//                    router.push('/form');
            },
        },
    }
</script>

<style scoped>
    div {
        margin-top: 50px;
    }

    .nav-item:active {
        color: #5BC0DE;
    }
</style>