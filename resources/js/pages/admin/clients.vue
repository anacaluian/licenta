<template>
    <div class="container-fluid ml-5 mr-5">
        <div class="row ml-2">
            <b-button class="add-client" v-b-modal.add><i class="fas fa-plus"></i> Add Client</b-button>
        </div>
        <div class="row ml-2 mt-4 mr-5">
            <b-table small dark ref="table" id="table" :items="data" :fields="fields">
                <template v-slot:cell(actions)="row">
                    <b-button v-b-modal.edit @click="selectClient(row.item.id)" pill class="outlined"
                              variant="outline-secondary">Edit
                    </b-button>
                    <b-button @click="deleteClient(row.item.id)" pill class="outlined" variant="outline-secondary">
                        Delete
                    </b-button>
                </template>
            </b-table>
        </div>
        <b-modal id="add"
                 okTitle="Add"
                 ok-only
                 @ok="createClient"
                 title="Create a new client"
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
            <b-form-group
                    class="custom"
                    label="Phone number:"
            >
                <b-form-input
                        v-model="form.phone"
                        required
                        placeholder="Enter phone number"
                ></b-form-input>
            </b-form-group>
        </b-modal>
        <b-modal id="edit"
                 okTitle="Edit"
                 ok-only
                 ref="editModal"
                 @ok="editClient"
                 title="Edit client"
        >
            <b-form-group
                    class="custom"
                    label="First name:"
            >
                <b-form-input
                        v-model="selectedClient.first_name"
                        required
                        placeholder="Enter first name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Last name:"
            >
                <b-form-input
                        v-model="selectedClient.last_name"
                        required
                        placeholder="Enter last name"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Email:"
            >
                <b-form-input
                        v-model="selectedClient.email"
                        required
                        type="email"
                        placeholder="Enter email"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
                    label="Phone number:"
            >
                <b-form-input
                        v-model="selectedClient.phone"
                        required
                        placeholder="Enter phone number"
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
                    phone:null,
                    role:3
                },
                selectedClient:{
                    id:null,
                    first_name:null,
                    last_name:null,
                    email:null,
                    phone:null,
                },
                data:[],
                fields:['first_name','last_name','email','phone','actions']
            }
        },
        mounted(){
            this.getClients();
        },
        methods:{
            getClients(){
                this.axios({
                    method: 'get',
                    url: laroute.route('clients', {}),
                }).then((response) => {
                    this.data = []
                    for (let client of response.data.original.clients) {
                        this.data.push({
                            id:client.id,
                            first_name: client.first_name,
                            last_name:  client.last_name,
                            email:  client.email,
                            phone:client.phone
                        })
                    }
                })
                    .catch((error) => console.log(error))
            },
            createClient(){
                this.$auth.register({
                    data:this.form,
                    success: function () {
                        this.getClients()
                    },
                    error: function (res) {
                        console.log(res.response.data.errors)
                    }
                })
            },
            selectClient(id){
                if (id && this.data) {
                    let client = this.data.filter((item) => item.id === id);
                    if (client){
                        this.selectedClient.id = id;
                        this.selectedClient.first_name = client[0].first_name;
                        this.selectedClient.last_name = client[0].last_name;
                        this.selectedClient.email = client[0].email;
                        this.selectedClient.phone = client[0].phone;
                        this.$refs.editModal.show()
                    }
                }
            },
            editClient(){
                let self = this;
                this.axios({
                    method: 'post',
                    url: laroute.route('clients.edit', {}),
                    data:this.selectedClient
                }).then((response) => {
                    this.getClients()
                })
                    .catch((error) => console.log(error))
            },
            deleteClient(id){
                let self = this;
                this.axios({
                    method: 'post',
                    url: laroute.route('clients.delete', {}),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.getClients()
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
    .add-client {
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