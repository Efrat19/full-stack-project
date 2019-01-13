import Vue from 'vue'
import VueRouter from 'vue-router'
import form from './Form.vue'
import home from './home.vue'
Vue.use(VueRouter);

const routes = [
    {path:'/',component:home},
    {path:'/form',component:form},
]

export default new VueRouter({mode: 'history', routes})
//     { path: '/', component: WelcomePage },
//     { path: '/signup', component: SignupPage },
//     { path: '/signin', component: SigninPage },
//     {
//         path: '/dashboard',
//         component: DashboardPage,
//         beforeEnter (to, from, next) {
//             if (store.state.idToken) {
//                 next()
//             } else {
//                 next('/signin')
//             }
//         }
//     }
