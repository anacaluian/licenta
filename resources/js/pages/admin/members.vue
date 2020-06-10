<template>
    <div class="container-fluid ml-5 mr-5">
        <div class="row ml-2">
            <b-button class="add-member" v-b-modal.add><i class="fas fa-plus"></i> Add Member</b-button>
        </div>
        <div class="row ml-2 mt-4 mr-5">
            <b-table small dark ref="table" id="table" :items="data" :fields="fields">
                <template v-slot:cell(actions)="row">
                    <b-button v-b-modal.edit @click="selectMember(row.item.id)" pill class="outlined"
                              variant="outline-secondary">Edit
                    </b-button>
                    <b-button @click="deleteMember(row.item.id)" pill class="outlined" variant="outline-secondary">
                        Delete
                    </b-button>
                </template>
            </b-table>
        </div>

        <b-modal id="add"
                 okTitle="Add"
                 ok-only
                 @ok="createMember"
                 title="Create a new member"
        >
            <b-form-group
                    class="custom"
                    label="First name:"
            >
                <b-form-input
                        v-model="form.first_name"
                        required
                        placeholder="Enter first name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Last name:"
            >
                <b-form-input
                        v-model="form.last_name"
                        required
                        placeholder="Enter last name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Email:"
            >
                <b-form-input
                        v-model="form.email"
                        required
                        type="email"
                        placeholder="Enter email"
                ></b-form-input>
            </b-form-group>
        </b-modal>
        <b-modal id="edit"
                 okTitle="Edit"
                 ok-only
                 @ok="editMember"
                 ref="editModal"
                 title="Edit member"
        >
            <b-form-group
                    class="custom"
                    label="First name:"
            >
                <b-form-input
                        v-model="selectedMember.first_name"
                        required
                        placeholder="Enter first name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Last name:"
            >
                <b-form-input
                        v-model="selectedMember.last_name"
                        required
                        placeholder="Enter last name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Email:"
            >
                <b-form-input
                        v-model="selectedMember.email"
                        required
                        type="email"
                        placeholder="Enter email"
                ></b-form-input>
            </b-form-group>
        </b-modal>
    </div>

</template>
<script>

    export default {
        data(){
            return{
                form:{
                    first_name:null,
                    last_name:null,
                    email:null,
                    role:2,
                },
                selectedMember:{
                    id:null,
                    first_name:null,
                    last_name:null,
                    email:null,
                },
                data:[],
                fields:['first_name','last_name','email','actions']
            }
        },
        mounted(){
            this.getMembers();
        },
        methods:{
            createMember(){
                var self = this
                this.$auth.register({
                    data:this.form,
                    success: function () {
                        this.getMembers()
                    },
                    error: function (res) {
                        console.log(res.response.data.errors)
                    }
                })
            },
            getMembers(){
                this.axios({
                    method: 'get',
                    url: laroute.route('members', {}),
                }).then((response) => {
                    this.data = []
                    for (let member of response.data.original.members) {
                        this.data.push({
                            id:member.id,
                            first_name: member.first_name,
                            last_name:  member.last_name,
                            email:  member.email
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            selectMember(id){
                if (id && this.data) {
                    let member = this.data.filter((item) => item.id === id);
                    if (member){
                        this.selectedMember.id = id;
                        this.selectedMember.first_name = member[0].first_name;
                        this.selectedMember.last_name = member[0].last_name;
                        this.selectedMember.email = member[0].email;
                        this.$refs.editModal.show()
                    }
                }
            },
            editMember(){
                let self = this;
                this.axios({
                    method: 'post',
                    url: laroute.route('members.edit', {}),
                    data:this.selectedMember
                }).then((response) => {
                    this.getMembers()
                })
                    .catch((error) => console.log(error))
            },
            deleteMember(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('members.delete', {}),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.getMembers()
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style scoped>
    input{
        background-color: #454d55;
        border-color:  #67FFC8 !important;
        color: white !important;

    }
    input:focus {
        background-color: #454d55;
        color: white;
    }
    .add-member {
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
