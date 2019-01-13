import {store} from './store'
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import {routes} from './routes.js'
import Vuelidate from 'vuelidate'
import axios from 'axios';

Vue.use(axios);
Vue.use(Vuelidate);
Vue.use(VueRouter);

export const router=new VueRouter({
    routes,
    mode:'history'
});
// router.onReady(() => {
//     //when router has finished the routing to the component
//     }
// );
// router.beforeEach((to, from, next) => {
//     if(store.getters.getEmail) {
//         next('/dashboard');
//     }
//     else {
//         next('login');
//     }
//     // before entering a component, kinda like beforeReady
// })
new Vue({
    el: '#app',
    router,
    store,
    validations: {},
    render: h => h(App)
})

router.onReady(function(){
    console.log('onready');
    if(localStorage.getItem('email')){
        store.state.status = JSON.parse(localStorage.getItem('status'));
        store.state.name = JSON.parse(localStorage.getItem('name'));
        store.state.phone = JSON.parse(localStorage.getItem('phone'));
        store.state.email = JSON.parse(localStorage.getItem('email'));
        store.state.dateOfBirth = JSON.parse(localStorage.getItem('dateOfBirth'));
        store.state.acceptTerms = JSON.parse(localStorage.getItem('acceptTerms'));
        store.state.acceptMailing = JSON.parse(localStorage.getItem('acceptMailing'));
    }
},function(){
    console.log('onReady catch');
})