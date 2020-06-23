<template>
    <div class="row ml-2">
        <b-card
                v-for="(list, index) in lists"
                :key="index"
                :title="index.replace(/_/g,' ')"
                tag="article"
                style="max-width: -moz-min-content; min-width: 13%;overflow-y: auto;
        overflow-x: hidden;"
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
                        <b-avatar id="task_avatar" v-if="element.assignee && element.assignee.profile_photo" :src="element.assignee.profile_photo"></b-avatar>
                        <b-avatar id="task_avatar_text" v-if="element.assignee && !element.assignee.profile_photo"  variant="primary"
                                  :text="element.assignee.first_name[0].toUpperCase()+element.assignee.last_name[0].toUpperCase()"></b-avatar>
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
                    :no-close-on-esc="true"
            >
                <b-button class="shadow-lg" id="close-btn" @click="closeTaskDetails"><i class="fas fa-times"></i></b-button>
                <div class="sidebar-content">
                    <div class="task-content">
                        <div class="row">
                            <div class="col-md-11">
                                <h4>#{{selectedTask.id}}: {{selectedTask.name}}</h4>
                            </div>
                            <span @click="deleteTask(selectedTask.id)"><i class="fas fa-trash-alt"></i></span>
                        </div>
                        <editor-content :editor="editor" />
                        <hr>
                        <h6><strong>Discussion</strong></h6>

                        <div class="comment-editor">
                            <editor-menu-bar :editor="comment" v-slot="{ commands, isActive, focused }">
                                <div
                                        class="menubar is-hidden"
                                        :class="{ 'is-focused': focused }"
                                >

                                    <b-button
                                            class="menubar__button"
                                            :class="{ 'is-active': isActive.bold() }"
                                            @click="commands.bold"
                                    >
                                        <i class="fas fa-bold"></i>
                                    </b-button>

                                    <button
                                            class="menubar__button"
                                            :class="{ 'is-active': isActive.italic() }"
                                            @click="commands.italic"
                                    >
                                        <i class="fas fa-italic"></i>
                                    </button>

                                    <button
                                            class="menubar__button"
                                            :class="{ 'is-active': isActive.strike() }"
                                            @click="commands.strike"
                                    >
                                        <i class="fas fa-strikethrough"></i>
                                    </button>

                                    <button
                                            class="menubar__button"
                                            :class="{ 'is-active': isActive.underline() }"
                                            @click="commands.underline"
                                    >
                                        <i class="fas fa-underline"></i>
                                    </button>
                                    <label
                                            class="menubar__button ml-2"
                                    >
                                        <i class="fas fa-paperclip"></i>
                                        <input type="file" style="display: none" ref="files" class="form-control-file"  multiple="multiple">
                                    </label>
                                </div>
                            </editor-menu-bar>
                            <div class="row">
                                <div class="col-md-8">
                                    <editor-content class="editor__content__comment " :editor="comment" @keyup.enter="sendComment"/>
                                </div>
                                <div>
                                    <b-button @click="sendComment" pill class="outlined" variant="outline-secondary">
                                        <i class="fas fa-share"></i>
                                    </b-button>
                                </div>
                            </div>
                        </div>
                        <div class="comment_section">
                            <div v-for="comment in taskComments">

                                <p>
                                    <b-avatar id="comment_avatar" v-if="comment.member.profile_photo" :src="comment.member.profile_photo"></b-avatar>
                                    <b-avatar id="comment_avatar_text" v-else  variant="primary"
                                              :text="comment.member.first_name[0].toUpperCase()+comment.member.last_name[0].toUpperCase()"></b-avatar>
                                    <strong class="ml-1 mr-1">
                                    {{comment.member.first_name}} {{comment.member.last_name[0]}}.
                                    </strong> {{comment.last_edit}}
                                    <span v-if="comment.user_id === $auth.user().id" @click="deleteComment(comment.id)" class="ml-2"><i class="fas fa-trash-alt"></i> </span>
                                </p>
                                <p  v-html="comment.comment"></p>
                                <preview v-if="comment.files.length" :files="comment.files"></preview>
                            </div>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="p-2">
                            <p><strong>Task List</strong></p>
                            <h5 class="task-prop">{{selectedTask.task_list}}</h5>
                        </div>
                        <div class="p-2">
                            <p><strong>Assignee</strong></p>
                            <div v-if="$auth.check(3)">
                                <h5 class="task-prop">{{ selectedTask.assignee ? selectedTask.assignee.first_name +' '+ selectedTask.assignee.last_name: 'No assignee set'}}</h5>
                            </div>
                            <div v-else>
                                <treeselect class="assignee-select"  placeholder="Select a member" v-model="selectedTask.assignee_id" :multiple="false" :options="members" />
                            </div>
                        </div>
                        <div class="p-2">
                            <p><strong>Due on</strong></p>
                            <date-picker id="picker"  class="picker" placeholder="Due On" v-model="selectedTask.due_on" valueType="format"></date-picker>
                        </div>
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
                <treeselect  class="assignee-select"  placeholder="Select a member" v-model="form.assignee" :multiple="false" :options="members" />
            </b-form-group>
            <!--<b-form-group-->
                    <!--class="custom"-->
                    <!--label="Label:"-->
            <!--&gt;-->
                <!--<b-form-input-->
                        <!--v-model="form.label"-->
                        <!--required-->
                        <!--placeholder="Choose label"-->
                <!--&gt;</b-form-input>-->
            <!--</b-form-group>-->
        </b-modal>
    </div>
</template>
<script>
    import draggable from 'vuedraggable'
    import { Editor, EditorContent, EditorMenuBar  } from 'tiptap';
    import {
        Bold,
        Italic,
        Link,
        Strike,
        Underline,
    } from 'tiptap-extensions';
    import preview from './image_preview'
    export default {
        components: {
            draggable,
            preview,
            EditorContent,
            EditorMenuBar,
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
                comment:null,
                editor:null,
                members: [],
                lists: {
                    backlog:[]
                },
                project_data:'',
                selectedTask:'',
                taskComments:[],
                today:''
            }
        },
         mounted() {
            this.getProject();
            this.getMembers();
            // this.getTasks();
             EventBus.$on('filter', (assignee,due_on) => {
                 this.getProject(assignee,due_on);
             });
            const now = new Date();
            this.today =  new Date(now.getFullYear(), now.getMonth(), now.getDate());
            this.comment = new Editor({
                extensions: [
                    new Bold(),
                    new Italic(),
                    new Link(),
                    new Strike(),
                    new Underline(),
                ],
                content: '',
            });
            this.editor = new Editor({
                content: '',
            })
        },
        beforeDestroy() {
            this.editor.destroy();
            this.comment.destroy()
        },
        methods: {
            getProject(assignee, due) {
                let data ={
                    id: this.$route.params.id
                };
                if (assignee){
                    data['assignee'] = assignee;
                }
                if (due){
                    data['due'] = due;
                }

                this.axios({
                    method: 'post',
                    url: laroute.route('projects.data', {}),
                    data:data
                }).then((response) => {
                    this.project_data = response.data.data;
                    this.$root.$emit('project', response.data.data.name);
                    if (response.data.data.tasks_list) {
                        let task_list = JSON.parse(response.data.data.tasks_list);
                        for (let name of task_list) {
                            this.lists[name.text] = [];
                        }
                    }
                    let tasks =  response.data.data.tasks
                    for(let task of tasks) {
                        this.lists[task.task_list].push(task);
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
                            id: member.member.id,
                            label: member.member.first_name + ' ' + member.member.last_name
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
                    this.getProject();
                })
                    .catch((error) => console.log(error))
            },
            addTask() {
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks.create', {}),
                    data: this.form
                }).then((response) => {
                    this.getProject();
                })
                    .catch((error) => console.log(error))
            },
            async showTaskDetails(task){
                 this.taskComments = [];
                 this.visible = true;
                this.selectedTask = task;
                this.editor.setContent(task.details);
              await this.getComments();
            },
            closeTaskDetails(){
                this.visible = false;
                let editor_json = this.editor.getJSON();
                let final = '';
                for (let content of  editor_json.content) {
                    if (content.content){
                        final +=  content.content[0].text + ' ';
                    }
                }
                this.selectedTask.details = final;
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks.update.task', {}),
                    data: this.selectedTask
                }).then((response) => {
                    this.getProject();
                })
                    .catch((error) => console.log(error))
            },
            getComments(){
                 this.axios({
                    method: 'get',
                    url: laroute.route('comments', { project: this.$route.params.id, task: this.selectedTask.id}),
                }).then((response) => {
                    this.taskComments = response.data.data;
                })
                    .catch((error) => console.log(error))
            },
            sendComment(){
                let comment_html = this.comment.getHTML();
                this.comment.clearContent();
                let formData = new FormData();
                for( var i = 0; i < this.$refs.files.files.length; i++ ){
                    let file = this.$refs.files.files[i];
                    formData.append('files[' + i + ']', file);
                }
                formData.append('user_id',this.$auth.user().id );
                formData.append('project_id',this.$route.params.id );
                formData.append('task_id', this.selectedTask.id);
                formData.append('comment',comment_html );
                this.axios({
                    method: 'post',
                    url: laroute.route('comments.create', {}),
                    headers: {
                         'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                }).then((response) => {
                    this.getComments();
                })
                    .catch((error) => console.log(error))
            },
            deleteComment(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('comments.delete', {} ),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.getComments();
                })
                    .catch((error) => console.log(error))
            },
            deleteTask(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks.delete', {} ),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.visible = false;
                    this.getProject();
                })
                    .catch((error) => console.log(error))            }
        }
    }
</script>
<style scoped>
    .picker{
        width: 100% !important;
    }
    .picker > div > input{
        color:#67FFC8 !important ;
    }
    strong{
        color: #CFCFD0;
    }
    .editor__content__comment{
        border: 1px solid #67FFC8 ;
        border-radius: 10px;
    }
    .menubar__button{
        background-color: transparent;
        border-color: transparent;
    }
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

    .custom-select{
        background-color: #454d55;
        border-color: #67FFC8 !important;
        color: #67FFC8;
    }


    .custom >>> legend {
        color: #67FFC8 !important;
    }

    .comment_section{
        position: absolute;
        padding-top: 5%;
        margin: 0;
        top: auto;
        left: 1.5%;
        width: 95%;
        height: 70%;
        overflow-y: auto;
        overflow-x: hidden;
        display: block;
    }
    .task-content{
        position: fixed;
        padding: 0;
        margin: 0;
        top: 5%;
        left: 51.5%;
        width: 32%;
        height: 92%;
        color: #CFCFD0;
    }
    .task-prop{
        text-transform: capitalize;
        font-weight: bold;
        color: #67FFC8;
    }
    .right-content{
        position: fixed;
        padding: 0;
        margin: 0;
        top: 3%;
        left: 84.5%;
        width: 15%;
        height: 92%;
    }
    .sidebar {
        position: fixed;
        right: 0;
        top: 15px;
        width: fit-content;
        min-width: 50%;
        z-index: 9999;
    }

    .sidebar-content {
        height: 95vh !important;
        margin-left: 20px;
    }

    .card-body {
        padding-left: 0;
        padding-right: 0;
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
    .el-icon-zoom-out:after {
        font-family: FontAwesome !important;
        content: "\f010";
    }
    .col-form-label{
        color: #67FFC8 !important;
    }
</style>
<style>
    .vue-treeselect__control{
        background: #373a44 !important;
        border-color:#67FFC8 !important; ;
        color:#67FFC8 !important;
    }

    .vue-treeselect__single-value{
        color:#67FFC8 !important;
    }

    #task-preview{
        border-top-left-radius: 10px !important;
        border-bottom-left-radius: 10px !important;
    }
    #close-btn{
        right: 51%;
        position: fixed;
        border-radius: 50%;
        top: 3%;
        background-color: #343a40;
        border-color: #343a40;
    }
    #close-btn > i:hover{
        transform: rotate(30deg);
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
    .el-image-viewer__close{
        right:97% !important;
    }
    #comment_avatar > span > img{
        width: 7% !important;
    }
    #comment_avatar_text {
        width: 6%;
        height: auto;
        display: inline-flex;
        justify-content: center;
        line-height: 2.5;
    }

    #task_avatar > span > img{
        width: 23%;
        height: auto;
        margin-right: 3px;
    }
    #task_avatar_text {
        width: 6%;
        height: auto;
        display: inline-flex;
        justify-content: center;
        line-height: 2.5;
        margin-right: 3px;

    }
</style>