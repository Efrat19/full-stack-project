import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex);
import {required,email,alpha,numeric,integer,maxValue} from 'vuelidate/lib/validators';
export const store=new Vuex.Store({
    state:{
        status:"Sign Up",
        name:"",
        email:"",
        phone:"",
        dateOfBirth:undefined,
        acceptTerms:false,
        acceptMailing:true
    },
    getters:{
        getStatus:function(state){
            return state.status;
        },
        getName:function(state){
            return state.name;
        },
        getEmail:function(state){
            return state.email;
        },
        getPhone:function(state){
            return state.phone;
        },
        getBirth:function(state){
            return state.dateOfBirth;
        },
        getTerms:function(state){
            return state.acceptTerms;
        },
        getMailing:function(state){
            return state.acceptMailing;
        }
    },
    mutations:{
        setStatus: function (state,pl) {
            state.status=pl;
        },
        setName: function (state,pl) {
            state.name=pl;
        },
        setEmail: function (state,pl) {
            state.email=pl;
        },
        setPhone: function (state,pl) {
            state.phone=pl;
        },
        setBirth: function (state,pl) {
            state.dateOfBirth=pl;
        },
        setTerms: function (state,pl) {
            state.acceptTerms=pl;
        },
        setMailing: function (state,pl) {
            state.acceptMailing=pl;
        },
    },
    actions: {
        setStatus: ({commit},pl)=>{
            commit('setStatus',pl);
        },
        setName: ({commit},pl)=>{
            commit('setName',pl);
        },
        setEmail: ({commit},pl)=>{
            commit('setEmail',pl);
        },
        setPhone: ({commit},pl)=>{
            commit('setPhone',pl);
        },
        setBirth: ({commit},pl)=>{
            commit('setBirth',pl);
        },
        setTerms: ({commit},pl)=>{
            commit('setTerms',pl);
        },
        setMailing: ({commit},pl)=>{
            commit('setMailing',pl);
        }
    },
});
