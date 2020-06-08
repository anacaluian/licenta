<template>
    <div class="profile shadow-lg">
        <h5 class="pl-5 pt-5">BASIC INFO</h5>
        <div class="p-4 row">
            <div class="col-md-3">
                <b-avatar id="avatar" v-if="$auth.check()" variant="primary"
                          :text="$auth.user().first_name[0].toUpperCase()+$auth.user().last_name[0].toUpperCase()"></b-avatar>
            </div>
            <div class="col-md-6">
                <b-form-group
                        class="custom"
                        label="Profile Photo"
                >
                    <label>
                        <span class=" btn  btn-secondary add-photo"> Choose File </span>
                        <input type="file" style="display: none" ref="files" class="form-control-file">
                    </label>
                </b-form-group>
            </div>
        </div>
        <b-form-group
                class="custom col-8"
                label="First Name*"
        >
            <b-form-input
                    v-model="form.first_name"
                    required
                    placeholder="First Name"
            ></b-form-input>
        </b-form-group>
        <b-form-group
                class="custom col-8"
                label="Last Name*"
        >
            <b-form-input
                    v-model="form.last_name"
                    required
                    placeholder="First Last"
            ></b-form-input>
        </b-form-group>
        <b-form-group
                class="custom col-8"
                label="Email Address*"
                description="This email is your login username, and it is required."
        >
            <b-form-input
                    v-model="form.email"
                    required
                    placeholder="Email Address"
            ></b-form-input>
        </b-form-group>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                form: {
                    first_name: this.$auth.user().first_name,
                    last_name: this.$auth.user().last_name,
                    email: this.$auth.user().email
                }
            }
        }
    }
</script>
<style scoped>
    h5{
        color: #67FFC8;
    }
    .add-photo {
        border-radius: 16px;
        border: 1px solid #67FFC8;
        background: transparent;
    }

    .profile {
        position: fixed;
        padding: 0;
        margin: 0;
        top: 6%;
        left: 34%;
        width: 32%;
        height: 92%;
        color: #CFCFD0;
        background: #373a44;
        border-radius: 16px;
    }

    .custom-select {
        background-color: #454d55;
        border-color: #67FFC8 !important;
        color: #67FFC8;
    }

    input {
        background-color: #454d55 !important;
        border-color: #67FFC8 !important;
        color: white !important;
    }

    input:focus {
        background-color: #454d55;
        color: white;
    }

    .custom >>> legend {
        color: #67FFC8 !important;
    }
</style>
<style>
    #avatar {
        display: inline-block !important;
        height: 6.5em !important;
        width: 6.5em !important;
    }

    #avatar > span {
        display: flex;
        justify-content: center;
        line-height: 6;
    }

</style>
