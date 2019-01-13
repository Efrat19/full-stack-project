<template>
    <div>
        <div class="container">
            <div class="row">
                <h2>Hi {{name}}! What would you like to do?</h2>
            </div>
            <br>
            <div class="row">
                <div class="form-inline">
                    <label class="mr-sm-8" for="checkall"><b>Show All</b></label>
                    <pre>   </pre>
                    <label class="switch">
                        <input class="switch-input" type="checkbox" id="checkall" v-model="showall"/>
                        <span class="switch-label" data-on="On" data-off="Off"></span>
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
            <div class="row">
                <label class="mr-sm-8" for="checkall"><b><h3>OR</h3></b></label>
            </div>
            <div class="row">
                <div class="form-inline">
                    <label class="mr-sm-8" for="inlineFormCustomSelect"><b>Search By:</b>
                        <pre>   </pre>
                    </label>
                    <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelect" v-model="searchBy"
                            disabled="true">
                        <option selected value="name">name</option>
                        <option value="email">email address</option>
                        <option value="phone">phone number</option>
                        <option value="mailing">mailing accepted</option>
                    </select>
                    <div v-show="searchBy=='mailing'" class="form-check form-check-inline" >
                        <pre>   </pre>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="true" v-model="mailingRadio">
                            <pre> </pre>
                            <label class="form-check-label" for="exampleRadios1">
                                yes
                            </label>
                        </div>
                        <pre>     </pre>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="false" v-model="mailingRadio">
                            <pre> </pre>
                            <label class="form-check-label" for="exampleRadios2">
                                no
                            </label>
                        </div>
                    </div>
                    <pre>   </pre>
                    <input v-show="!(searchBy=='mailing')" type="text" v-model="value" class="form-control" :placeholder="ph"
                           id="inlineFormCustomSelectInput" disabled="true">
                    <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                    </label>
                    <button class="btn btn-info" @click="select" id="inlineFormCustomSelectButton" disabled="true">GO
                    </button>
                    <!--:disabled="$v.$invalid" @blur="$v.email.$touch()":type="type"-->
                </div>
            </div>
            <br>
            <div class="row">
                <table>
                    <div class="table">
                        <div v-for="(user,index) in users">
                            <tbody>
                            <tr class="list-group-item">
                                <!--<thead>-->
                                <!--<tr>-->
                                <!--<th></th>-->
                                <!--<th></th>-->
                                <!--<th></th>-->
                                <!--<th></th>-->
                                <!--</tr>-->
                                <!--</thead>-->
                            <tbody>
                            <td>{{user.name}}</td>
                            <td></td>
                            <td></td>
                            <td>
                                <button :id="'btnExpand'+index" type="button" class="btn btn-info"
                                        @click="expand(index)">
                                    {{!user.expand?'expand':'collapse'}}
                                </button>
                            </td>
                            </tbody>
                            </tr>
                            <tr :id="'details'+index" v-show="user.expand"
                                class="list-group-item list-group-item-warning">
                                <!--<thead>-->
                                <!--<tr>-->
                                <!--<th>email</th>-->
                                <!--<th>phone</th>-->
                                <!--<th>date of birth</th>-->
                                <!--<th></th>-->
                                <!--</tr>-->
                                <!--</thead>-->
                                <tbody>
                                <td>{{user.email}}</td>
                                <td>{{user.phone}}</td>
                                <td>{{user.dateOfBirth}}</td>
                                <td>
                                    <button type="button" class="btn btn-warning" @click="edit(index)"
                                            :disabled="!user.edit">Edit Detailes
                                    </button>
                                </td>
                                </tbody>
                            </tr>
                            </tbody>
                        </div>
                    </div>
                </table>
            </div>
            <div class="row">
                <div v-if="serverErrs" class="panel panel-danger" id="ErrLog">
                    <div class="panel-heading"><b>Some problems popped up from the servers...</b></div>
                    <div class="panel-heading" v-for="error in serverErrs" v-if="error!=''">{{error}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vuex from 'vuex'
    import {store} from './store';
    import {router} from './main.js';
    import axios from 'axios';
    import Vuelidate from 'vuelidate';
    import {required, email, integer, maxValue, maxLength, minLength} from 'vuelidate/lib/validators';

    export default {
        data: function () {
            return {
                users: undefined,
                searchBy: 'name',
                type: 'name',
                value: '',
                serverErrs: undefined,
                ph: 'type a name',
                showall: true,
                customSelection: false,
                mailingRadio:true
            }
        },
        watch: {
            'searchBy': function () {
                if (this.searchBy == 'email')
                    this.type = 'email';
                else
                    this.type='text';
//                if (this.searchBy == 'name')
//                    this.type = 'name';
//                if (this.searchBy == 'phone')
//                    this.type = 'phone';
                this.ph = 'type a' + (this.searchBy == 'email' ? 'n ' : ' ') + this.searchBy;
                if(this.searchBy=='mailing') {
                    document.getElementById('inlineFormCustomSelectInput').display = false;
                }
//                console.log('searchby: ' + this.searchBy + ' type: ' + this.type + 'value:' + this.value);
            },
            'showall': function () {
                if (this.showall) {
                    document.getElementById('inlineFormCustomSelect').disabled = true;
                    document.getElementById('inlineFormCustomSelectInput').disabled = true;
                    document.getElementById('inlineFormCustomSelectButton').disabled = true;
                    document.getElementById('exampleRadios1').disabled = true;
                    document.getElementById('exampleRadios2').disabled = true;
                    this.showAllReq();
                }
                else {
                    this.users=undefined;
                    document.getElementById('inlineFormCustomSelect').disabled = false;
                    document.getElementById('inlineFormCustomSelectInput').disabled = false;
                    document.getElementById('inlineFormCustomSelectButton').disabled = false;
                    document.getElementById('exampleRadios1').disabled = false;
                    document.getElementById('exampleRadios2').disabled = false;
                }
            },
//            'mailingRadio':function () {
//                console.log(this.mailingRadio);
//            }
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
        },
        created() {
            console.log(this.email);
            if (JSON.parse(localStorage.getItem('email')) == null)
                router.push('/form');
            console.log(JSON.parse(localStorage.getItem('email')));
            this.showAllReq();
        },
        computed: {
            status: {
                get(){
                    return this.$store.state.status
                }, set(pl){
                    this.$store.dispatch('setStatus', pl)
                }
            },
            email: {
                get(){
                    return this.$store.state.email
                }, set(pl){
                    this.$store.dispatch('setEmail', pl)
                }
            },
            name: {
                get(){
                    return this.$store.state.name
                }, set(pl){
                    this.$store.dispatch('setName', pl)
                }
            },
        },
        methods: {
            showAllReq: function () {
                axios.get('/api/showDB').then(res => {
                    this.display((res.data))
                }).catch(res => {
                    console.log(res)
                });
            },
//                searchByType:function(val){
//                    this.searchBy=val;
//                },
            select: function () {
                this.serverErrs = undefined;
                this.users=undefined;
                let selectParams = new URLSearchParams();
                selectParams.append('column', this.searchBy);
                selectParams.append('value', this.value);
                axios({
                    method: 'post',
                    url: '/api/select',
                    data: selectParams
                })
                    .then((res) => {
                        console.log("then:" + res);
                        this.checkRes(res.data);
                    }).catch((error) => {
                    console.log("catch:" + error
                    )
                });
            },
            checkRes: function (data) {
                console.log(data['status']);
                if (data['status'] == 'success')
                    this.display(data['content']);
                else {
                    this.serverErrs = this.serverErrs ? this.serverErrs : [];
                    for (let i = 0; i < 5; i++) {
                        this.serverErrs[i] = data['content'][i];
                        console.log(data['content'][i]);
                    }
                }
            },
            display: function (data) {
                this.users=undefined;
                this.users=this.users?this.users:[];
                for (let i = 0; i < data.length; i++) {
                    this.users.push(data[i]);
                    this.users[i].name = data[i].name;
                    this.users[i].email = data[i].email;
                    this.users[i].phone = data[i].phone;
                    this.users[i].dateOfBirth = data[i].dateOfBirth;
                    this.users[i].expand = false;
                    this.users[i].edit = this.email == this.users[i].email ? true : false;
//                    console.log(this.users[i].name);
                }
            },
            expand: function (index) {
                if (this.users[index].expand == false) {
                    this.users[index].expand = true;
                    document.getElementById('details' + index).style = 'display:block';
                    document.getElementById('btnExpand' + index).innerText = 'collapse';
                }
                else {
                    this.users[index].expand = false;
                    document.getElementById('details' + index).style = 'display:none';
                    document.getElementById('btnExpand' + index).innerText = 'expand';
                }
                console.log(this.users[index].expand);
            },
            edit: function (index) {
                //switch to Save Changes status
                //display form cmp
                this.status = "Save Changes";
                console.log(this.email);
                router.push('/form');
            }
        },
        beforeRouteLeave (to, from, next){
            this.users = undefined;
            next();
        },
//        beforeRouteEnter: (to, from, next) => {
//                        next(vm => {
//                            vm.$store.state.email==""});
//                        if((vm)=>{vm.$store.state.email==""}) {
//                            router.push('/form');
//                        }
//                        else {
//                            console.log('next');
//            next(true);
//                        }
//                            }
    }
</script>

<style scoped>
    /*#details{*/
    /*display: none;*/
    /*}*/
    td {
        width: 200px;
        text-align: left;
    }
    .container{
        color: #4e4e4e;
    }
    /*th {*/
    /*display: none;*/
    /*height: 0px;*/
    /*}*/
    form-inline {
        display: inline;
    }

    table:active {
        background-color: #4e4e4e;
    }

    br {
        display: block;
    }

    .switch {
        position: relative;
        display: block;
        vertical-align: top;
        width: 100px;
        height: 30px;
        padding: 3px;
        margin: 0 10px 10px 0;
        background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px);
        background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF 25px);
        border-radius: 18px;
        box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        box-sizing: content-box;
    }

    .switch-input {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        box-sizing: content-box;
    }

    .switch-label {
        position: relative;
        display: block;
        height: inherit;
        font-size: 10px;
        text-transform: uppercase;
        background: #eceeef;
        border-radius: inherit;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
        box-sizing: content-box;
    }

    .switch-label:before, .switch-label:after {
        position: absolute;
        top: 50%;
        margin-top: -.5em;
        line-height: 1;
        -webkit-transition: inherit;
        -moz-transition: inherit;
        -o-transition: inherit;
        transition: inherit;
        box-sizing: content-box;
    }

    .switch-label:before {
        content: attr(data-off);
        right: 11px;
        color: #aaaaaa;
        text-shadow: 0 1px rgba(255, 255, 255, 0.5);
    }

    .switch-label:after {
        content: attr(data-on);
        left: 11px;
        color: #FFFFFF;
        text-shadow: 0 1px rgba(0, 0, 0, 0.2);
        opacity: 0;
    }

    .switch-input:checked ~ .switch-label {
        background: #5BC0DE;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
    }

    .switch-input:checked ~ .switch-label:before {
        opacity: 0;
    }

    .switch-input:checked ~ .switch-label:after {
        opacity: 1;
    }

    .switch-handle {
        position: absolute;
        top: 4px;
        left: 4px;
        width: 28px;
        height: 28px;
        background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
        background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0);
        border-radius: 100%;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
    }

    .switch-handle:before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -6px 0 0 -6px;
        width: 12px;
        height: 12px;
        background: linear-gradient(to bottom, #eeeeee, #FFFFFF);
        background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF);
        border-radius: 6px;
        box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
    }

    .switch-input:checked ~ .switch-handle {
        left: 74px;
        box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
    }

    /* Transition
    ========================== */
    .switch-label, .switch-handle {
        transition: All 0.3s ease;
        -webkit-transition: All 0.3s ease;
        -moz-transition: All 0.3s ease;
        -o-transition: All 0.3s ease;
    }
</style>