<template>
    <div class="container-fluid ml-5 mr-5">
        <div class="row ml-2">
            <b-button class="add-project" v-b-modal.add ><i class="fas fa-plus"></i> Add Project</b-button>
        </div>
        <div class="row ml-2 mt-4">
            <b-table  dark ref="table" :items="data" :fields="fields">
                <template v-slot:cell(state)="row">
                    <b-badge v-if="row.item.state == 'in_progress'" pill variant="success">In progress</b-badge>
                    <b-badge v-if="row.item.state == 'completed'" pill variant="danger">Completed</b-badge>
                </template>
                <template v-slot:cell(actions)="row">
                </template>
                <template v-slot:cell(members)="row">
                    <div v-for="member in row.item.members">
                        <p>{{member.label}}</p>
                    </div>
                </template>
            </b-table>
        </div>
        <b-modal id="add"
                 okTitle="Add"
                 ok-only
                 @ok="createProjects"
        >
            <b-form-group
                    label="Project's name:"
            >
                <b-form-input
                        v-model="form.name"
                        required
                        placeholder="Enter project's name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
            label="Owner:"
            >
            <b-form-input
                    v-model="form.owner"
                    required
                    placeholder="Enter owner"
            ></b-form-input>
            </b-form-group> <b-form-group
            label="Members:"
            >
            <treeselect
                    v-model="form.members"
                    :multiple="true"
                    :options="members"
            ></treeselect>
            </b-form-group>
        </b-modal>
    </div>
</template>
<script>
    export default {
        mounted(){
            this.getMembers();
            this.getProjects();
        },
        data() {
            return{
            form:{
                name:null,
                owner:null,
                members:null,
            },
                members:[],
                data:[],
                fields:[
                    {
                        key: 'name',
                        label:'Project name',
                        sortable: true
                    },
                    {
                        key: 'owner',
                        label:'Owner',
                        sortable: true
                    },
                    {
                        key: 'members',
                        label:'Project members',
                        sortable: true
                    },
                    {
                        key: 'state',
                        label:'Project state',
                        sortable: true
                    },
                    {
                        key: 'actions',
                        label:'Actions',
                    }
                ],
                isLoading: false,
        }
        },
        methods:{
            getMembers(){
                this.axios({
                    method:'get',
                    url:laroute.route('members', {}),
                }).then((response) => {
                    for(let member of response.data.members){
                        this.members.push({
                            id:member.id,
                            label: member.first_name + ' ' + member.last_name
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            getProjects(){
                this.axios({
                    method:'get',
                    url:laroute.route('projects', {}),
                }).then((response) => {
                    for(let project of response.data.data){
                        let members = this.members.filter((item) => {
                            if (project.members.includes(item.id)) {
                                return item;
                            }
                        })
                        this.data.push({
                            name:project.name,
                            owner:project.owner,
                            state:project.state,
                            members:members,
                            clients:project.clients,
                            created:project.created_at
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            createProjects(){
                this.axios({
                    method:'post',
                    url:laroute.route('projects.create', {}),
                    data:this.form
                }).then((response) => {
                    this.$refs.table.refresh()
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style scoped>
    button{
       border-radius: 16px;
        background-color: #67FFC8 !important;
    }

</style>
<style>
    .modal-content{
        background-color: #373a44 !important;
    }
    .col-form-label{
        color: #67FFC8 !important;
    }
    .modal-footer button{
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }
</style>