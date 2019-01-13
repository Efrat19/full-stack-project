import form from './Form.vue';
import home from './home.vue';
import terms from './assets/Terms.vue';
import inside from './inside.vue'
export const routes = [
    {path:'/',component:home},
    {path:'/form',component:form},
    {path:'/form/terms',component:terms},
    {path:'/inside',component:inside},
    // {path:'/',component:signup}
];
