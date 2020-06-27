<template>
    <div class="content container-fluid shadow-lg">
        <h5 class="p-2" style="color: #B9B9B9 !important">Projects Members</h5>
        <b-table class="table-sm" :dark="true" thead-class="hidden_header"  hover :items="members" :fields="fields">
            <template v-slot:cell(first_name)="row">
                <p>
                    <b-avatar id="member_avatar" v-if="row.item.profile_photo" :src="row.item.profile_photo"></b-avatar>
                    <b-avatar id="member_avatar"  v-else  variant="primary"
                              :text="row.item.first_name[0].toUpperCase()+row.item.last_name[0].toUpperCase()"></b-avatar>
                    <strong class="ml-1 mr-1">
                        {{row.item.first_name}} {{row.item.last_name}}
                    </strong></p>
            </template>
            <template v-slot:cell(role)="row">
               <p v-if="row.item.role == 2">Member</p>
               <p v-else>Admin <i class="fas fa-crown"></i></p>
            </template>
        </b-table>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                members:[],
                fields:['first_name','email','role']
            }
        },
        mounted(){
            this.getMembers();
        },
        methods:{
            getMembers(){
                this.axios({
                    method: 'get',
                    url: laroute.route('members', {}),
                }).then((response) => {
                    this.members = response.data.original.members;
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style scoped>
    .content{
        position: fixed;
        padding: 20px;
        margin: 0;
        top: auto;
        left: 3%;
        width: 95%;
        height: 90%;
        background-color:#373a44 !important;
        border-radius: 10px;
    }

</style>
<style>
    .hidden_header {
        display: none !important;
    }
    #member_avatar{
        display: inline-block;
    }
</style>