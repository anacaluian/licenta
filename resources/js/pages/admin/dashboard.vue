<template>
    <div class="container-fluid ml-5 mr-5">
        <div class="row ml-2">
            <b-button class="add-project" v-b-modal.add><i class="fas fa-plus"></i> Add Project</b-button>
        </div>
        <div class="row ml-2 mt-4 mr-5">
            <b-table small dark ref="table" id="table" :items="data" :fields="fields">
                <template v-slot:cell(state)="row">
                    <b-badge v-if="row.item.state == 'in_progress'" pill variant="success">In progress</b-badge>
                    <b-badge v-if="row.item.state == 'completed'" pill variant="danger">Completed</b-badge>
                </template>
                <template v-slot:cell(members)="row">
                    <div v-for="member in row.item.members_project" >
                        {{member.first_name + ' ' + member.last_name}}
                    </div>
                </template>
                <template v-slot:cell(clients)="row">
                    <div v-for="client in row.item.clients_project" >
                        {{client.first_name + ' ' + client.last_name}}
                    </div>
                </template>
                <template v-slot:cell(actions)="row">
                    <b-button v-b-modal.edit @click="selectProject(row.item.id)" pill class="outlined"
                              variant="outline-secondary">Edit
                    </b-button>
                    <b-button @click="changeState(row.item.id,row.item.state)" v-if="row.item.state == 'in_progress'"
                              pill class="outlined" variant="outline-secondary">Disable
                    </b-button>
                    <b-button v-else @click="changeState(row.item.id,row.item.state)" pill class="outlined"
                              variant="outline-secondary">Activate
                    </b-button>
                    <b-button @click="deleteProject(row.item.id)" pill class="outlined" variant="outline-secondary">
                        Delete
                    </b-button>
                </template>
            </b-table>
        </div>
        <!--CREATE MODAL-->
        <b-modal id="add"
                 okTitle="Add"
                 ok-only
                 @ok="createProjects"
                 title="Create a new project"
        >
            <b-form-group
                    class="custom"
                    label="Project's name:"
            >
                <b-form-input
                        v-model="form.name"
                        required
                        placeholder="Enter project's name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Owner:"
            >
                <b-form-input
                        v-model="form.owner"
                        required
                        placeholder="Enter owner"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Support email:"
            >
                <b-form-input
                        v-model="form.support_email"
                        required
                        type="email"
                        placeholder="Enter support email"
                ></b-form-input>
            </b-form-group>
            <b-form-group
            <b-form-group
                    class="custom"
                    label="Lists:"
            >
                <tags
                        class="custom-tags"
                        v-model="tasks"
                        :tags="form.tasks"
                        @tags-changed="newTags => form.tasks = newTags"
                />
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Members:"
            >
                <treeselect
                        v-model="form.members"
                        :multiple="true"
                        :options="members"
                ></treeselect>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Clients:"
            >
                <treeselect
                        v-model="form.clients"
                        :multiple="true"
                        :options="clients"
                ></treeselect>
            </b-form-group>
        </b-modal>
        <!--EDIT MODAL-->
        <b-modal id="edit"
                 okTitle="Edit"
                 ok-only
                 @ok="editProject"
                 title="Edit project"
        >
            <b-form-group
                    class="custom"
                    label="Project's name:"
            >
                <b-form-input
                        v-model="selectedProject.name"
                        required
                        placeholder="Enter project's name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Owner:"
            >
                <b-form-input
                        v-model="selectedProject.owner"
                        required
                        placeholder="Enter owner"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Support email:"
            >
                <b-form-input
                        v-model="selectedProject.support_email"
                        required
                        type="email"
                        placeholder="Enter support email"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Lists:"
            >
                <tags
                        v-model="selectedProject.edit"
                        class="custom-tags"
                        :tags="selectedProject.tasks"
                        @tags-changed="(newTags) => { selectedProject.tasks = newTags }"
                        @before-adding-tag="makeLowerCase"
                />
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Members:"
            >
                <treeselect
                        v-model="selectedProject.members"
                        :multiple="true"
                        :options="members"
                ></treeselect>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Clients:"
            >
                <treeselect
                        v-model="selectedProject.clients"
                        :multiple="true"
                        :options="clients"
                ></treeselect>
            </b-form-group>
        </b-modal>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.getMembers();
            this.getProjects();
            this.getClients();
        },
        data() {
            return {
                tasks:'',
                tasks_edit:'',
                form: {
                    name: null,
                    owner: null,
                    edit:[],
                    tasks:[],
                    members: null,
                    clients: null,
                    support_email: null
                },
                selectedProject: {
                    edit:'',
                    id: null,
                    name: null,
                    owner: null,
                    tasks:[],
                    members: [],
                    clients: [],
                    support_email: null
                },
                members: [],
                clients: [],
                data: [],
                fields: ['name', 'owner', 'support_email','state','members','clients','created','actions'],
                isLoading: false,
            }
        },
        methods: {
            makeLowerCase(obj){
                obj.tag.text = obj.tag.text.replace(/ /g,"_");
                obj.tag.text = obj.tag.text.toLowerCase();
                obj.addTag();
            },
            getMembers() {
                this.axios({
                    method: 'get',
                    url: laroute.route('members', {}),
                }).then((response) => {
                    for (let member of response.data.original.members) {
                        this.members.push({
                            id: member.id,
                            label: member.first_name + ' ' + member.last_name
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            getClients() {
                this.axios({
                    method: 'get',
                    url: laroute.route('clients', {}),
                }).then((response) => {
                    for (let client of response.data.original.clients) {
                        this.clients.push({
                            id: client.id,
                            label: client.first_name + ' ' + client.last_name
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            getProjects() {
                this.axios({
                    method: 'get',
                    url: laroute.route('projects', {}),
                }).then((response) => {
                    this.data = response.data.data;

                })
                    .catch((error) => console.log(error))
            },
            createProjects() {
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.create', {}),
                    data: this.form
                }).then((response) => {
                    this.getProjects();
                })
                    .catch((error) => console.log(error))
            },
            selectProject(id) {
                let data = this.data.filter((item) => item.id === id);
                console.log(data);
                this.selectedProject.id = id;
                this.selectedProject.name = data[0].name;
                this.selectedProject.owner = data[0].owner;
                this.selectedProject.support_email = data[0].support_email;
                this.selectedProject.members = [];
                this.selectedProject.clients = [];
                for (let member of data[0].members_project) {
                    this.selectedProject.members.push(member.id);
                }
                for (let client of data[0].clients_project) {
                    this.selectedProject.clients.push(client.id);
                }
                this.selectedProject.tasks =[];
                if (data[0].tasks_list){
                    this.selectedProject.tasks = JSON.parse(data[0].tasks_list);
                }
            },
            editProject() {
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.edit', {}),
                    data: this.selectedProject
                }).then((response) => {
                    this.getProjects();
                })
                    .catch((error) => console.log(error))
            },
            changeState(id, state) {
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.state', {}),
                    data: {
                        id: id,
                        state: state
                    }
                }).then((response) => {
                    this.getProjects();
                })
                    .catch((error) => console.log(error))
            },
            deleteProject(id) {
                console.log(id)
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.delete', {}),
                    data: {
                        id: id,
                    }
                }).then((response) => {
                    this.getProjects();
                })
                    .catch((error) => console.log(error))
            },
        }
    }
</script>
<style scoped>
    .vue-tags-input >>> .ti-tag{
        background-color: #67FFC8 !important;
    }
    .custom-tags{
        background-color: #373a44 !important;
    }
    .ti-new-tag-input.ti-valid{
        background-color: #454d55 !important;
    }
    input{
        background-color: #454d55 !important;
        border-color:  #67FFC8 !important;
        color: white !important;
    }
    input:focus {
        background-color: #454d55;
        color: white;
    }
    .add-project {
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }

    .custom >>> legend {
        color: #67FFC8 !important;
    }

    .outlined {
        border-color: #67FFC8 !important;
        color: white !important;
    }

    .outlined:hover {
        border-color: #67FFC8 !important;
        background-color: #67FFC8 !important;
        color: white !important;
    }

</style>
<style>

    .modal-content {
        background-color: #373a44 !important;
    }

    .modal-title {
        color: #67FFC8 !important;
    }

    .modal-footer button {
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }
</style>