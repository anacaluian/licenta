<template >
    <div v-if="$auth.ready()" class="login" >
        <div class="center">
            <b-card
                    title="Sign in"
                    tag="article"
                    style="max-width: 20rem;"
                    class="mb-2 shadow-lg p-3 mb-5 bg-white rounded col-8"
            >
                <b-card-text>
                    <div class="alert alert-danger" v-if="has_error">
                        <p>Credentiale introduse nu se potrivesc cu cele din baza noastra de date.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b-form-group
                                    label="Email*"
                            >
                                <b-form-input
                                        v-model="email"
                                        type="email"
                                        required
                                        placeholder="Enter email"
                                ></b-form-input>
                            </b-form-group>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b-form-group
                                label="Password*">
                                <b-form-input
                                        v-model="password"
                                        type="password"
                                        required
                                        placeholder="Enter password"
                                        v-on:keyup.enter="login"
                                ></b-form-input>
                            </b-form-group>
                        </div>
                    </div>
                </b-card-text>
                <div class="row">
                    <div class="col-md-12">
                        <b-button class="col-12 login-btn" @click="login">Sign in</b-button>
                    </div>
                </div>
            </b-card>
        </div>
        <div class="row center">
            <router-link :to="{name:'forgot.password'}">Forgot Password?</router-link>
        </div>
    </div>
</template>
<script>
    export default {
        name:'login',
        data() {
            return {
                email: null,
                password: null,
                has_error: false
            }
        },
        methods:
            {
                login(){
                    // get the redirect object
                    var self = this;
                    this.$auth
                        .login({
                            data: {
                                email: self.email,
                                password: self.password,
                            },
                            rememberMe: true,
                            fetchUser: true
                        })
                        .then(() => {
                            switch(this.$auth.user().roles[0]){
                                case 1:
                                    this.$router.push({name:'admin.dashboard'});
                                    break;
                                case 2:
                                    this.$router.push({name:'member.dashboard'});
                                    break;
                                case 3:
                                    this.$router.push({name:'member.dashboard'});
                                    break;
                            }
                        })
                        .catch((error) => {
                            this.has_error = true;
                            let self = this;
                            setTimeout(function(){ self.has_error = false; }, 5000);
                        });

                }
            }
    }
</script>
<style scoped>
    .login{
        position:fixed;
        padding:0;
        margin:0;

        top:0;
        left:0;

        width: 100%;
        height: 100%;
        background-color: white;
    }
    .center{
        display: flex;
        justify-content:center;
        padding-top: 13%;
        position:relative;
    }
    .card-title{
        font-weight:bold;
    }
    .col-form-label{
        font-weight:bold;
    }
    .login-btn{
        background-color: #5D2BFF ;
        border-color:#5D2BFF;
    }
    button{
        border-radius:16px;
    }
    a{
        text-decoration:none;
    }
</style>