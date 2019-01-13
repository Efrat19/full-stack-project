<template>
    <div>
        <form method="post" @submit.prevent="SubmitLead">
            <div class="input">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" v-model="name" @blur="validate(name)" placeholder="your name here" >
                <p v-if="nameErr!=''">{{nameErr}}</p>
            </div>
            <div class="input">
                <label for="email">E-mail:</label>
                <input type="email"  class="form-control" id="email" name="email" v-model="email" @blur="validate(email)" placeholder="example@example">
                <p v-if="emailErr!=''">{{emailErr}}</p>
            </div>
            <div class="input">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" v-model="phone" @blur="validate(phone)" placeholder="your phone number" >
                <p v-if="phoneErr!=''">{{phoneErr}}</p>
            </div>
            <div class="input">
                <label for="dateOfBirth">Date Of Birth:</label>
                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" v-model="dateOfBirth" @blur="validate(dateOfBirth)">
                <p v-if="dateOfBirthErr!=''">{{dateOfBirthErr}}</p>
            </div>
            <div class="form-group">
                <input type="checkbox" id="acceptTerms" name="acceptTerms" v-model="acceptTerms" @blur="validate(acceptTerms)">
                <label for="acceptTerms">Accept <router-link to="/form/terms" target="_blank">Terms</router-link></label>
                <p v-if="acceptTermsErr!=''">You should accept the terms before you move on</p>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" :value="status" :disabled="checkValid">
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import Vuelidate from 'vuelidate';
    import {mapGetters} from 'vuex';
    //import {required,email,alpha,numeric,integer,maxValue,maxLength,minLength,sameAs} from 'vuelidate/lib/validators';
    export default {
        data :function() {
            return {
                nameErr:"",
                emailErr:"",
                phoneErr:"",
                dateOfBirthErr:"",
                acceptTermsErr:""
            }
        },
        computed:{
            // ...mapGetters(['getStatus','getName','getEmail','getPhone','getBirth','getTerms']),
            status:{get(){return this.$store.state.status},set(pl){this.$store.dispatch('setStatus',pl)}},
            name:{get(){return this.$store.state.name},set(pl){this.$store.dispatch('setName',pl)}},
            email:{get(){return this.$store.state.email},set(pl){this.$store.dispatch('setEmail',pl)}},
            phone:{get(){return this.$store.state.phone},set(pl){this.$store.dispatch('setPhone',pl)}},
            dateOfBirth:{get(){return this.$store.state.dateOfBirth},set(pl){this.$store.dispatch('setBirth',pl)}},
            acceptTerms:{get(){return this.$store.state.acceptTerms},set(pl){this.$store.dispatch('setTerms',pl)}},
        },
//        validations: {
//            name:{
//                required,
//                alpha,
//                minLength:minLength(2),
//                maxLength:maxLength(20),
//            },
//            email: {
//                required,
//                email,
//            },
//            phone:{
//                required,
//                numeric,
////           ("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/")
//            },
//            dateOfBirth:{
//                required,
//                unique:dateOfBirth=>{
//                    let unixNow=new Date().getTime() / 1000;
//                    const unix18Years=567993600;
//                    if(dateOfBirth.getTime()/1000>=unixNow-unix18Years)
//                        return false;
//                    return true;
//                }
//            },
//            acceptTerms:{
//                required
//            },
//        },
        methods:{
            SubmitLead:function(){
                const formData = {
                    mode:this.status=="Sign Up"?"insert":"update",
                    name:this.name,
                    email:this.email,
                    phone:this.phone,
                    dateOfBirth:this.dateOfBirth,
                    acceptTerms:this.acceptTerms
                }
                console.log('SubmitLead');
                axios.post('',formData).then(res=>console.log(res)).catch(err=>console.log(err));
            },
            validate:function (field) {
                if(!this[field]) {
                    data[field + 'Err'] = "This field must not be empty.";
                    return;
                }
                switch (field)
                {
                    case 'name':{
                        if(this.name.match("/^[a-zA-Zא-ת]*$/"))
                            this.nameErr="";
                        else
                            this.nameErr="illegal name";
                        break;
                    }
                    case 'email':{
                        if(this.email.match("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@"))
                            this.emailErr="";
                        else
                            this.emailErr="illegal email";
                        break;
                    }
                    case 'phone':{
                        if(this.phone.match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im"))
                            this.phoneErr="";
                        else
                            this.phoneErr="illegal phone";
                        break;
                    }
                    case 'dateOfBirth':{
                        let unixNow=new Date().getTime() / 1000;
                        const unix18Years=567993600;
                        if(this.dateOfBirth.getTime()/1000<unixNow-unix18Years)
                            this.dateOfBirthErr="";
                        else
                            this.dateOfBirthErr="illegal age";
                        break;
                    }
                    case 'acceptTerms':{
                        if(this.acceptTerms==true)
                            this.acceptTermsErr="";
                        break;
                    }
                }
            },
            checkValid:function () {
                if(this.nameErr==""&&this.emailErr==""&&this.phoneErr==""&&this.dateOfBirthErr==""&&this.acceptTermsErr=="")
                    return true;
                return false;
            }
        }
    }
</script>

<style scoped>
    .signup-form {
        width: 400px;
        margin: 30px auto;
        border: 1px solid #eee;
        padding: 20px;
        box-shadow: 0 2px 3px #ccc;
    }

    .input {
        margin: 10px auto;
    }

    .input label {
        display: block;
        color: #4e4e4e;
        margin-bottom: 6px;
    }

    .input.inline label {
        display: inline;
    }

    .input input {
        font: inherit;
        width: 100%;
        padding: 6px 12px;
        box-sizing: border-box;
        border: 1px solid #ccc;
    }

    .input.inline input {
        width: auto;
    }

    .input input:focus {
        outline: none;
        border: 1px solid #521751;
        background-color: #eee;
    }

    .input.invalid label {
        color: red;
    }

    .input.invalid input {
        border: 1px solid red;
        background-color: #ffc9aa;
    }

    .input select {
        border: 1px solid #ccc;
        font: inherit;
    }

    .hobbies button {
        border: 1px solid #521751;
        background: #521751;
        color: white;
        padding: 6px;
        font: inherit;
        cursor: pointer;
    }

    .hobbies button:hover,
    .hobbies button:active {
        background-color: #8d4288;
    }

    .hobbies input {
        width: 90%;
    }

    .submit button {
        border: 1px solid #521751;
        color: #521751;
        padding: 10px 20px;
        font: inherit;
        cursor: pointer;
    }

    .submit button:hover,
    .submit button:active {
        background-color: #521751;
        color: white;
    }

    .submit button[disabled],
    .submit button[disabled]:hover,
    .submit button[disabled]:active {
        border: 1px solid #ccc;
        background-color: transparent;
        color: #ccc;
        cursor: not-allowed;
    }
</style>
