<template>
    <div class="row ml-2">
        <b-card
                v-for="(list, index) in lists"
                :key="index"
                :title="index.replace(/_/g,' ')"
                tag="article"
                style="max-width: 20rem; min-width: 13%;"
                class="mb-2 card  mr-3"
        >
            <b-card-text>
                <draggable class="list-group" group="people" :list="list" @end="update">
                    <div
                            class="list-group-item"
                            v-for="(element, index) in list"
                            aria-controls="task-preview"
                            aria-expanded="true"
                            @click="showTaskDetails(element)"
                            :key="element.name"
                    >
                        #{{element.id}}: {{ element.name }}
                    </div>
                </draggable>
            </b-card-text>
            <b-card-footer v-b-modal.add>
                <i class="fas fa-plus"></i>
                Add task
            </b-card-footer>
        </b-card>
        <div class="sidebar">
            <b-sidebar
                    id="task-preview"
                    v-model="visible"
                    bg-variant="dark"
                    :no-header-close="true"
                    text-variant="light"
            >
                <b-button class="shadow-lg" id="close-btn" @click="visible=false"><i class="fas fa-times"></i></b-button>
                <div class="sidebar-content">
                    <div class="task-content">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>#{{selectedTask.id}}: {{selectedTask.name}}</h4>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <b-button class="edit-btn" @click="edit_description = !edit_description"><i class="fas fa-edit"></i></b-button>
                            </div>
                        </div>
                        <b-form-textarea v-if="edit_description"
                                id="textarea"
                                v-model="text"
                                placeholder="Enter something..."
                                rows="3"
                                max-rows="6"
                        ></b-form-textarea>
                        <p>AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA</p>
                        <hr>
                        <h6><strong>Discussion</strong></h6>
                        <b-form-input
                                v-model="comment"
                                class="mt-3 col-8"
                                required
                                placeholder="Write a comment..."
                        ></b-form-input>
                        <div class="comment_section"></div>

                    </div>
                </div>
            </b-sidebar>
        </div>
        <b-modal id="add"
                 okTitle="Add"
                 ok-only
                 @ok="addTask"
                 ref="addModal"
                 title="Add task"
        >
            <b-form-group
                    class="custom"
                    label="Task name:"
            >
                <b-form-input
                        v-model="form.name"
                        required
                        placeholder="Enter task name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    label="Assignee:">
                <b-form-select
                        v-model="form.assignee"
                        :options="members"
                        required
                ></b-form-select>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Label:"
            >
                <b-form-input
                        v-model="form.label"
                        required
                        placeholder="Choose label"
                ></b-form-input>
            </b-form-group>
        </b-modal>
    </div>
</template>
<script>
    import draggable from 'vuedraggable'
    import BButton from "buefy/src/components/button/Button";

    export default {
        components: {
            BButton,
            draggable,
        },
        props: {
            title: String,
        },
        data() {
            return {
                form: {
                    name: null,
                    label: null,
                    assignee: null,
                    project_id: this.$route.params.id

                },
                edit_description:false,
                visible:false,
                comment:'',
                members: [],
                lists: [],
                project_data:'',
                selectedTask:''
            }
        },
        mounted() {
            this.getProject();
            this.getMembers();
            this.getTasks();
        },
        methods: {
            getProject() {
                this.axios({
                    method: 'get',
                    url: laroute.route('projects.data', {id: this.$route.params.id}),
                }).then((response) => {
                    this.project_data = response.data.data;
                })
                    .catch((error) => console.log(error))
            },
            getTasks() {
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks', {}),
                    params: {
                        project_id: this.$route.params.id
                    }
                }).then((response) => {
                    this.lists = response.data.data;
                    for (let name of JSON.parse(this.project_data.tasks_list)) {
                        if (!Object.keys(this.lists).includes(name.text)) {
                            this.lists[name.text] = []
                        }
                    }
                })
                    .catch((error) => console.log(error))
            },
            getMembers() {
                this.axios({
                    method: 'post',
                    url: laroute.route('projects.members', {}),
                    params: {
                        project_id: this.$route.params.id
                    }
                }).then((response) => {
                    for (let member of response.data.data) {
                        this.members.push({
                            value: member.member.id,
                            text: member.member.first_name + ' ' + member.member.last_name
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            log: function (evt) {
                window.console.log(evt);
            },
            update(){
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks.update', {}),
                    data: this.lists
                }).then((response) => {
                })
                    .catch((error) => console.log(error))
            },
            addTask() {
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks.create', {}),
                    data: this.form
                }).then((response) => {
                })
                    .catch((error) => console.log(error))
            },
            showTaskDetails(task){
                this.visible = !this.visible;
                this.selectedTask = task;
            }
        }
    }
</script>
<style scoped>
    i{
        color:#67FFC8 !important ;
    }
    .edit-btn{
        background-color: #373a44;
        border: none;
    }
    input {
        background-color: #454d55;
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

    .task-content{
        position: fixed;
        padding: 0;
        margin: 0;
        top: 5%;
        left: 51%;
        width: 35%;
        height: 92%;
    }

    .sidebar {
        position: fixed;
        right: 0;
        top: 15px;
        width: fit-content;
        /*border-bottom-left-radius: 10px;*/
        /*border-top-left-radius: 10px;*/
        min-width: 50%;
    }

    .sidebar-content {
        height: 95vh !important;
        margin-left: 20px;
    }

    .card-body {
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0;
    }

    .card-title {
        margin-left: 10px;
        font-weight: bold;
        color: #CFCFD0;
        text-transform: capitalize;
    }

    .card-footer {
        color: #67FFC8;
        font-weight: bold;
        background-color: transparent;
    }

    .card-footer:hover {
        transform: translateX(15px);
        transition-delay: 10ms;
    }

    .card {
        border-radius: 10px;
        padding: 10px;
        background-color: #373a44;
        height: fit-content;
    }

    .list-group {
        border-radius: 0;
    }

    .list-group-item {
        background-color: #373a44;
        color: #CFCFD0;
    }
</style>
<style>
    #task-preview{
        border-top-left-radius: 10px !important;
        border-bottom-left-radius: 10px !important;
    }
    #textarea{
        background-color: #454d55;
        border-color: #67FFC8 ;
    }
    #close-btn{
        right: 51%;
        position: fixed;
        border-radius: 50%;
        top: 3%;
        background-color: #343a40;
        border-color: #343a40;
    }
</style>