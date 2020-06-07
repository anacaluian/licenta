<template>
    <div>
        <b-button  v-b-modal.time class="add-time"><i class="fas fa-plus"></i> New Time Record</b-button>
        <b-modal id="time" title="Add New Time Record" @ok="addTime">
            <b-form-group>
                <treeselect  class="assignee-select"  placeholder="Select a task" v-model="form.task" :multiple="false" :options="tasks" />
            </b-form-group>
            <b-form-group>
                <b-form-datepicker id="datepicker" v-model="form.date" class="mb-2"></b-form-datepicker>
            </b-form-group>
            <b-form-group
                    class="custom"
            >
                <b-form-input
                        v-model="form.time"
                        required
                        placeholder="Enter time, eg 1:30 or 1.50"
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    class="custom"
            >
                <b-form-textarea
                        id="textarea"
                        v-model="form.description"
                        placeholder="Description"
                        rows="3"
                        max-rows="6"
                ></b-form-textarea>

            </b-form-group>
        </b-modal>
    </div>

</template>
<script>
    export default {
        data(){
            return{
                form:{
                    time:'',
                    description:'',
                    task:null,
                    date:null
                },
                tasks:[]
            }
        },
        mounted(){
            this.getTasks();
        },
        methods:{
            getTasks() {
                this.axios({
                    method: 'post',
                    url: laroute.route('tasks', {}),
                    params: {
                        project_id: this.$route.params.id
                    }
                }).then((response) => {
                    const data = response.data.data;
                    for (let list in data){
                        for( const index  in data[list]){
                            this.tasks.push({
                                id: data[list][index].id,
                                label: data[list][index].name
                            })

                        }
                    }
                })
                    .catch((error) => console.log(error))
            },
            addTime(){
                console.log('here')
            }
        }
    }
</script>
<style scoped>
.add-time{
    border-radius: 16px;
    background-color: #67FFC8 !important;
}

input,textarea {
    background-color: #454d55;
    border-color: #67FFC8 !important;
    color: white !important;
}

input:focus,textarea:focus {
    background-color: #454d55;
    color: white;
}

.custom >>> legend {
    color: #67FFC8 !important;
}
</style>