<template>
    <div class="content container-fluid shadow-lg ">
        <div class="content-header">
            <div class="row up-nav">
                <router-link  :to="{ name : 'project' }">
                    Tasks
                </router-link>
                <router-link  :to="{ name : 'project.discussions' }">
                    Discussions
                </router-link>
            </div>
            <hr>
        </div>
        <div class="content-middle">
            <router-view></router-view>
            <div  v-if="$route.name == 'project'">
                <taskList title="Backlog" :lists="lists"> </taskList>
            </div>
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
                lists:{
                    list1: [
                        { name: "Jesus", id: 1 },
                        { name: "Paul", id: 2 },
                        { name: "Peter", id: 3 }
                    ],
                    list2: [
                        { name: "Luc", id: 5 },
                        { name: "Thomas", id: 6 },
                        { name: "John", id: 7 }
                    ],
                    list3:[
                        { name: "Luc", id: 5 },
                        { name: "Thomas", id: 6 },
                        { name: "John", id: 7 }
                    ]
                }



            }
        },
        mounted(){

        },
        methods:{

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
        left: 3%;
        width: 95%;
        height: 90%;
        background-color:#2c2e38 !important;
        border-radius: 10px;
        inset: 0 -1px 0 0 #25262B;
    }
    .content-header{
        position: fixed;
        padding: 0;
        margin: 0;
        top: auto;
        left: 3%;
        width: 95%;
        height: 12%;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-color:#373a44 !important;
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