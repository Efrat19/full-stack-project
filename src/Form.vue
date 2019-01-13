<template>
    <div class="signupform">
        <form method="post" @submit.prevent="SubmitLead">
            <div class="input" :class="{invalid: $v.name.$error}">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                       v-model="name" @blur="$v.name.$touch()" placeholder="your name here" @focus="fireAlerts">
                <p v-if="!$v.name.required&&alertsOn">This field must not be empty.</p>
                <p v-if="!$v.name.validateName&&alertsOn&&$v.name.required">Name must be alphabetical</p>
                <p v-if="!$v.name.minLength || !$v.name.maxLength">Name should contain 2 to 20 characters</p>
            </div>
            <div :class="{invalid: $v.email.$error}" class="input">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" v-model="email"
                       @blur="$v.email.$touch()" placeholder="example@example" @focus="fireAlerts"
                       :disabled="status=='Save Changes'">
                <p v-if="!$v.email.email">Please provide a valid email address.</p>
                <p v-if="!$v.email.required&&alertsOn">This field must not be empty.</p>
            </div>
            <div class="input" :class="{invalid: $v.phone.$error}">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" v-model="phone" @blur="$v.phone.$touch()"
                       placeholder="your phone number" @focus="fireAlerts">
                <p v-if="!$v.phone.required&&alertsOn">This field must not be empty.</p>
                <p v-if="!$v.phone.validatePhone&&$v.phone.required">Please provide a valid phone number.</p>
            </div>
            <div class="input" :class="{invalid: $v.dateOfBirth.$error}">
                <label for="dateOfBirth">Date Of Birth:</label>
                <input type="date" class="form-control" id="dateOfBirth" name="dateOfBirth" v-model="dateOfBirth"
                       @change="$v.dateOfBirth.$touch()" @focus="fireAlerts">
                <p v-if="!$v.dateOfBirth.required">This field must not be empty.</p>
                <p v-if="!$v.dateOfBirth.validateDateOfBirth&&alertsOn">Illegal Date of Birth.</p>
            </div>
            <div class="form-group">
                <input type="checkbox" id="acceptTerms" name="acceptTerms" v-model="acceptTerms"
                       @change="$v.acceptTerms.$touch()" @focus="fireAlerts">
                <label for="acceptTerms">Accept
                    <router-link to="/form/terms" target="_blank">Terms</router-link>
                </label>
                <p v-if="!$v.acceptTerms.validateAcceptTerms&&alertsOn">You should accept the terms before you move
                    on</p>
            </div>
            <div class="form-group">
                <input type="checkbox" id="acceptMailing" name="acceptMailing" v-model="acceptMailing" @change="report"
                       @focus="fireAlerts">
                <label for="acceptMailing">Accept Mailing</label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" :value="status" :disabled="$v.$invalid">
            </div>
        </form>
        <div v-if="serverErrs" class="alert alert-danger" role="alert" id="ErrLog">
            <div class="panel-heading"><b>Some problems popped up from the servers...</b></div>
            <div class="panel-heading" v-for="error in serverErrs" v-if="error!=''">{{error}}</div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import {router} from './main.js';
    import Vuelidate from 'vuelidate';
    import {required, email, integer, maxValue, maxLength, minLength} from 'vuelidate/lib/validators';
    export default {
        data: function () {
            return {
                alertsOn: false,
                serverErrs: undefined,
                unixTimeChecked: Math.round((new Date()).getTime() / 1000),
                unixTimeMailingChecked: Math.round((new Date()).getTime() / 1000)
            }
        },
        created(){
            axios.get('/api/db').then(res => {
            });
            if (this.email == '')
                this.status = 'Sign Up';
            else
                this.status = 'Save Changes';
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
        validations: {
            name: {
                required,
                //alpha,
                minLength: minLength(2),
                maxLength: maxLength(20),
                validateName: function () {
                    var re = /^([a-zA-Zא-ת]+[']*)+([\s]?([a-zA-Zא-ת]+[']*)+)+$/;
                    if (re.test(this.name))
                        return true;
                    else
                        return false;
                }
            },
            email: {
                required,
                email,
            },
            phone: {
                required,
                validatePhone: function () {
                    var re = /^05\d{8}$/;///^(?:(?:(\+?972|\(\+?972\)|\+?\(972\))(?:\s|\.|-)?([1-9]\d?))|(0[23489]{1})|(0[57]{1}[0-9]))(?:\s|\.|-)?([^0\D]{1}\d{2}(?:\s|\.|-)?\d{4})$/im;
                    if (re.test(this.phone))
                        return true;
                    else
                        return false;
                }
            },
            dateOfBirth: {
                required,
                validateDateOfBirth: function () {
                    let unixNow = parseInt(new Date().getTime() / 1000);
                    const unix18Years = parseInt(567993600);
                    const unix100Years = parseInt(3155760000);
                    if ((new Date(this.dateOfBirth).getTime() / 1000) >= unixNow - unix18Years || (new Date(this.dateOfBirth).getTime() / 1000) <= unixNow - unix100Years)
                        return false;
                    return true;
                }
            },
            acceptTerms: {
                required,
                validateAcceptTerms: function () {
                    if (this.acceptTerms == true)
                        return true;
                    else
                        return false;
                }
            },
        },
        watch: {
            'acceptTerms': function () {
                this.unixTimeChecked = Math.round((new Date()).getTime() / 1000);
            },
            'acceptMailing': function () {
                this.unixTimeMailingChecked = Math.round((new Date()).getTime() / 1000);
            }
        },
        methods: {
            report: function () {
                console.log('accept mailing:' + this.acceptMailing);
            },
            SubmitLead: function () {
                this.serverErrs = undefined;
                let formData = new URLSearchParams();
                formData.append('mode', this.status == ("Sign Up") ? "insert" : "update");
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('phone', this.phone);
                formData.append('dateOfBirth', new Date(this.dateOfBirth).getTime() / 1000);
                formData.append('acceptTerms', this.acceptTerms);
                formData.append('unixTimeChecked', this.unixTimeChecked);
                formData.append('acceptMailing', this.acceptMailing);
                formData.append('unixTimeMailingChecked', this.unixTimeMailingChecked);
                axios({
                    method: 'post',
                    url: '/api/postLead',
                    data: formData
                })
                    .then((res) => {
                        console.log("then:" + res);
                        this.directRes(res.data)
                    }).catch((error) => {
                    console.log("catch:" + error
                    )
                });

            },

            fireAlerts: function () {
                this.alertsOn = true;
            },
            directRes: function (data) {
//                    direct the response to success method or to failure
                console.log('directRes:' + data['status']);
                if (data['status'] == ('success'))
                    this.success(data);
                else
                    this.failure(data);
            },
            success: function (data) {
                //load the store with user details from db
                this.name = data['content']['name'];
                this.email = data['content']['email'];
                this.phone = data['content']['phone'];
                this.dateOfBirth = data['content']['dateOfBirth'];
                localStorage.setItem('status', JSON.stringify(this.status));
                localStorage.setItem('name', JSON.stringify(this.name));
                localStorage.setItem('email', JSON.stringify(this.email));
                localStorage.setItem('phone', JSON.stringify(this.phone));
                localStorage.setItem('dateOfBirth', JSON.stringify(this.dateOfBirth));
                localStorage.setItem('acceptTerms', JSON.stringify(this.acceptTerms));
                localStorage.setItem('acceptMailing', JSON.stringify(this.acceptMailing));

                let msg = this.status == 'Sign Up' ? 'Your in' : 'Changes accepted';
                alert(msg);
                router.push('/inside');
            },
            failure: function (data) {
                this.serverErrs = this.serverErrs ? this.serverErrs : [];
                for (let i = 0; i < 5; i++) {
                    this.serverErrs[i] = data['content'][i];
                    console.log(data['content'][i]);
                }
            }
        }
    }
</script>

<style scoped>
    .signupform {
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

    /*p{*/
    /*color: #a9a9a9;*/
    /*}*/
</style>
