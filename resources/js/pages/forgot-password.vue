<template>
    <div class="forgot-password">
        <div class="center">
            <b-card
                    title="Forgot Password?"
                    tag="article"
                    style="max-width: 20rem;"
                    class="mb-2 shadow-lg p-3 mb-5 bg-white rounded col-8"
            >
                <b-card-text>
                    <router-link :to="{name:'home'}">Return to the login screen</router-link>
                    <div class="row">
                        <div class="col-md-12">
                            <b-form-group
                                    label="Email*"
                                    description="Enter your email address and reset your password."
                            >
                                <b-form-input
                                        v-model="email"
                                        type="email"
                                        required
                                        placeholder="Email"
                                ></b-form-input>
                            </b-form-group>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <b-button @click="recover" class="col-12 recover-btn">Recover</b-button>
                        </div>
                    </div>
                </b-card-text>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
            </b-card>
        </div>
    </div>
</template>
<script>
    export default {
        name:'forgot',
        data() {
            return {
                email: null,
            }
        },
        methods:
            {
                recover(){
                    this.axios({
                        method:'post',
                        url:laroute.route('forgot-password', {}),
                        data:{
                            email: this.email
                        }
                    }).then((response) => {

                    })
                        .catch((error) => this.has_error=true)
                }
            }
    }
</script>
<style scoped>
    .forgot-password{
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
        padding-top: 15%;
        position:relative;
    }
    .card-title{
        font-weight:bold;
    }
    .recover-btn{
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
