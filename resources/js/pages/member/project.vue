<template>
    <div class="content container-fluid shadow-lg ">
        <div class="content-header">
            <div class="row up-nav">
                <router-link  :to="{ name : 'project' }">
                    Tasks
                </router-link>
                <router-link  :to="{ name : 'project.notes' }">
                    Notes
                </router-link>
                <router-link  :to="{ name : 'project.files' }">
                    Files
                </router-link>
                <router-link v-if="!$auth.check(3)"  :to="{ name : 'project.time' }">
                    Time
                </router-link>
                <router-link v-if="!$auth.check(3)"  :to="{ name : 'project.activity' }">
                    Activity
                </router-link>
            </div>
            <hr>
            <div  v-if="$route.name == 'project'"class="row">
                <treeselect style="left:1%" class="col-sm-2" v-model="assignee" placeholder="Filter Assignee" :multiple="false" :options="members"></treeselect>
                <date-picker id="picker" class="col-sm-2"  placeholder="Filter Due On Date" v-model="due_on" valueType="format"></date-picker>
                <div class="ml-auto mr-4">
                    <b-dropdown id="people"  :text="'People(' + members.length + ')'">
                        <b-dropdown-item v-for="member in members" :key="member.id">
                            <b-avatar id="member_avatar" v-if="member.photo" :src="member.photo"></b-avatar>
                            <b-avatar id="member_avatar"  v-else  variant="primary"
                                      :text="member.label.split(' ')[0][0].toUpperCase() + member.label.split(' ')[1][0].toUpperCase()"></b-avatar>
                            {{member.label}}
                        </b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>

        </div>
        <div class="content-middle">
            <router-view></router-view>
            <taskList v-if="$route.name && $route.name === 'project'"> </taskList>
        </div>
    </div>
</template>
<script>
    import taskList from './../../components/task_list';
    export default {
        components: {
            taskList,
        },
        data(){
            return{
                assignee:null,
                due_on:null,
                members:[],
            }
        },
        mounted(){
            this.getMembers();
        },
        watch: {
            assignee: function () {
                EventBus.$emit('filter',this.assignee,this.due_on);
            },
            due_on: function () {
                EventBus.$emit('filter',this.assignee,this.due_on);
            },
        },
        methods:{
            getMembers(){
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.members', {}),
                    params: {
                        project_id: this.$route.params.id
                    }
                }).then((response) => {
                    for (let member of response.data.data) {
                        this.members.push({
                            id: member.member.id,
                            label: member.member.first_name + ' ' + member.member.last_name,
                            photo:member.member.profile_photo
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
        }
    }
</script>
<style scoped>
    .card-body{
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0;
    }
    .card-title{
        margin-left: 10px;
        font-weight: bold;
        color:#CFCFD0;
    }
    .card-footer{
        color:#67FFC8;
        font-weight: bold;
    }
    .card{
        border-radius: 10px;
        background-color: #373a44;
    }
    .list-group{
        border-radius: 0;
    }
    .list-group-item{
        background-color: #373a44;
        color:#CFCFD0;
    }
    a{
        color: #D1D1D3;
        font-weight: bold;
        margin-right: 20px;
        text-decoration:none !important;

    }
     .router-link-exact-active{
        color: #67FFC8 !important;
        border-bottom: 3px  #67FFC8 solid;
         text-decoration:none;
         padding-bottom: 10px;
    }

    .router-link-active:hover{
        text-decoration:none !important;
    }

    .content{
        position: fixed;
        padding: 0;
        margin: 0;
        top: auto;
        left: 4%;
        width: 95%;
        height: 88%;
        background-color:#2c2e38 !important;
        border-radius: 10px;
        inset: 0 -1px 0 0 #25262B;
    }
    .content-header{
        position: fixed;
        padding: 0;
        margin: 0;
        top: auto;
        left: 4%;
        width: 95%;
        height: 12%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-color:#373a44 !important;
        z-index: 40;
    }
    .content-middle{
        position: absolute;
        padding: 20px;
        margin: 0;
        top: 14%;
        width: 100%;
        height: 86%;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        background-color: #2c2e38 !important;
        overflow-y: auto;
        overflow-x: auto;
    }
    .up-nav{
        margin-left: 40px;
        margin-top: 25px;
    }
    hr {
        border: 0;
        clear:both;
        display:block;
        width: 95%;
        background-color: black;
        height: 1px;
        margin-top: 0;
    }

</style>
<style>
    #picker > div > input{
        background-color: #373a44 !important;
        border-color: #67FFC8 !important;
        color: #67FFC8 !important;
    }
    .vue-treeselect__placeholder{
        color: #67FFC8 !important;
    }
    #people > button{
        background-color:  transparent !important;
        border-color: #67FFC8 !important;
        color: #67FFC8 !important;
    }
</style>
