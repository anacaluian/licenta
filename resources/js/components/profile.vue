<template>
    <div class="profile shadow-lg">
        <h5 class="pl-5 pt-5">BASIC INFO</h5>
        <hr>
        <div class="p-4 row">
            <div class="col-md-3">
                <b-avatar v-if="$auth.user().profile_photo" :src="$auth.user().profile_photo"></b-avatar>
                <b-avatar v-else id="avatar"  variant="primary"
                          :text="$auth.user().first_name[0].toUpperCase()+$auth.user().last_name[0].toUpperCase()"></b-avatar>
            </div>
            <div class="col-md-6">
                <b-form-group
                        class="custom"
                        label="Profile Photo"
                >
                    <label>
                        <span class=" btn  btn-secondary add-photo"> Choose File </span>
                        <input  @change="addPhoto" type="file" style="display: none" ref="files" class="form-control-file">
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
        <div class="p-3">
            <b-button @click="save" class="save">Save Changes </b-button>
        </div>
        <h5 class="pl-5 pt-5">CHANGE PASSWORD</h5>
        <hr>
        <b-form-group
                class="custom col-8"
                label="Password"
        >
            <b-form-input
                    v-model="pass"
                    required
                    type="password"
            ></b-form-input>
        </b-form-group>
        <b-form-group
                class="custom col-8"
                label="Confirm Password"
        >
            <b-form-input
                    v-model="password_confirmation "
                    required
                    type="password"
            ></b-form-input>
            <div class="p-3">
                <b-button @click="changePassword" class="save">Change Password </b-button>
            </div>
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
                    email: this.$auth.user().email,
                    user_id:this.$auth.user().id
                },
                pass:'',
                password_confirmation :''
            }
        },
        methods:{
            changePassword(){
                this.axios({
                    method: 'post',
                    url: laroute.route('user.password', {}),
                    data: {
                        id:this.$auth.user().id,
                        password:this.pass,
                        password_confirmation :this.password_confirmation
                    }
                }).then((response) => {
                    this.$toast.open({
                        message: 'Password changed!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                })
                    .catch((error) =>
                        this.$toast.open({
                            message: error.message,
                            type: 'error',
                            position: 'bottom-right'
                        })
                    )
            },
            addPhoto(){
                let formData = new FormData();
                let file = this.$refs.files.files[0];
                formData.append('file', file);
                formData.append('user_id', this.$auth.user().id);
                this.axios({
                    method: 'post',
                    url: laroute.route('profile.photo', {}),
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                }).then((response) => {
                    this.$toast.open({
                        message: 'Photo changed!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                })
                    .catch((error) =>
                        this.$toast.open({
                            message: error.message,
                            type: 'error',
                            position: 'bottom-right'
                        }))
            },
            save(){
                this.axios({
                    method: 'post',
                    url: laroute.route('user.update', {}),
                    data:this.form
                }).then((response) => {
                    this.$toast.open({
                        message: 'Profile updated!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                })
                    .catch((error) =>
                        this.$toast.open({
                            message: error.members,
                            type: 'error',
                            position: 'bottom-right'
                        })
                    )
            }
        }
    }
</script>
<style scoped>
    .save{
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }
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
        overflow-y: scroll;
        overflow-x: hidden;
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
        line-height: 3.5;
    }

    #avatar > span > span{
        font-size: 188%;
    }

    .b-avatar-img > img{
        border-radius: 50%;
        height: auto;
        width: 100%;
    }

</style>
