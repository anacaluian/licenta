<template>
    <div class="container-fluid ml-5 all" >
        <div class="row ml-2">
            <b-button @click="sync" class="sync mr-2">Sync Emails</b-button>
            <b-button @click="add" class="sync">Add emails</b-button>
            <a hidden id="redirect"  href="" target="_blank"></a>
        </div>
        <b-modal ref="code" title="Verify auth"  @ok="generateToken" ok-only  hide-header-close>
            <b-form-group
                    class="custom"
                    label="Enter verification code: "
            >
                <b-form-input
                        v-model="verification_code"
                        required
                        placeholder="Verification code"
                ></b-form-input>
            </b-form-group>
        </b-modal>
        <div class="col-4 mt-4">
            <treeselect
                    v-model="project"
                    :multiple="false"
                    :options="projects"
            ></treeselect>
        </div>
        <div class="mt-4">
            <b-table class="mt-2" dark hover :items="items" :fields="fields">
                <template v-slot:cell(actions)="row">
                    <b-button v-if="row.item.task"
                              @click="remove(row.item.id)"  pill class="outlined" variant="outline-secondary">Remove task
                </b-button>
                    <b-button v-else @click="post(row.item.id)"
                              pill class="outlined" variant="outline-secondary">Add as task
                    </b-button>
                    <b-button @click="deleteEmail(row.item.email_id)"
                              pill class="outlined" variant="outline-secondary">Delete email
                    </b-button>
                </template>
            </b-table>
        </div>

    </div>
</template>
<script>
    export default {
        data(){
            return{
                emails:[],
                items:[],
                verification_code:"",
                project:null,
                projects:[],
                fields: ['from', 'subject', 'message','received_at','actions'],
            }
        },
        mounted(){
            this.getEmails();
        },
        watch:{
            project: function () {
                this.items =  this.emails[this.project];
            }
        },
        methods:{
            getEmails(){
                this.axios({
                    method: 'get',
                    url: laroute.route('emails', {}),
                }).then((response) => {
                    this.emails = response.data.data;
                    this.projects = [];
                    for (let project in response.data.data) {
                        this.projects.push({
                            id:project,
                            label: project
                        });
                        this.project = project;
                        this.items =  this.emails[this.project];
                    }

                })
                    .catch((error) => console.log(error))
            },
            sync(){
                this.axios({
                    method: 'get',
                    url: laroute.route('emails.sync', {}),
                }).then((response) => {
                    this.$toast.open({
                        message: 'Gmail synced!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                    this.getEmails()
                })
                    .catch((error) =>
                        this.$toast.open({
                        message: Object.values(error.response.data.errors)[0][0],
                        type: 'error',
                        position: 'bottom-right'
                    }))
            },
            post(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('emails.task', {}),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.$toast.open({
                        message: 'Task posted!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                    this.getEmails()
                })
                    .catch((error) =>  this.$toast.open({
                        message: Object.values(error.response.data.errors)[0][0],
                        type: 'error',
                        position: 'bottom-right'
                    }))
            },
            remove(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('emails.remove', {}),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.$toast.open({
                        message: 'Task removed!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                    this.getEmails()
                })
                    .catch((error) =>  this.$toast.open({
                        message: Object.values(error.response.data.errors)[0][0],
                        type: 'error',
                        position: 'bottom-right'
                    }))
            },
            deleteEmail(email_id){
                this.axios({
                    method: 'post',
                    url: laroute.route('emails.delete', {}),
                    data:{
                        email_id:email_id
                    }
                }).then((response) => {
                    this.$toast.open({
                        message: 'Email deleted!',
                        type: 'success',
                        position: 'bottom-right'
                    });
                    this.getEmails()
                })
                    .catch((error) =>
                        this.$toast.open({
                            message: Object.values(error.response.data.errors)[0][0],
                            type: 'error',
                            position: 'bottom-right'
                        })
                    )
            },
            add(){
                this.axios({
                    method: 'post',
                    url: laroute.route('emails.request', {}),
                }).then((response) => {
                    document.getElementById('redirect').setAttribute('href',response.data['authLink']);
                    document.getElementById('redirect').click();
                    this.$refs['code'].show()
                })
                    .catch((error) =>  this.$toast.open({
                        message: Object.values(error.response.data.errors)[0][0],
                        type: 'error',
                        position: 'bottom-right'
                    }))
            },
            generateToken(){
                this.axios({
                    method: 'post',
                    url: laroute.route('emails.token', {}),
                    data:{
                        code:decodeURIComponent(this.verification_code)
                    }
                }).then((response) => {
                })
                    .catch((error) => console.log(error))
                this.verification_code = '';
            }
        }
    }
</script>
<style scoped>
    .sync {
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }
    .all{
        width: 96%;
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
    input{
        background-color: #454d55 !important;
        border-color:  #67FFC8 !important;
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