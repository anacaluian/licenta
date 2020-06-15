<template>
    <div>
        <b-button  v-b-modal.time class="add-time"><i class="fas fa-plus"></i> New Time Record</b-button>
        <b-modal id="time" title="Add New Time Record" @ok="addTime">
            <b-form-group>
                <treeselect  class="assignee-select"  placeholder="Select a task" v-model="form.task" :multiple="false" :options="tasks" />
            </b-form-group>
            <b-form-group>
                <!--<b-form-datepicker id="datepicker" v-model="form.date" class="mb-2"></b-form-datepicker>-->
                <date-picker id="picker"  class="picker" placeholder="Select Date" v-model="form.date" valueType="format"></date-picker>

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
        <div class="mt-4">
            <GSTC :config="data" @state="onState" />

        </div>
    </div>

</template>
<script>
    import GSTC from "vue-gantt-schedule-timeline-calendar";
    let subs = [];
    export default {
        components: {
            GSTC
        },
        data() {
            return {


                data:{
                    list: {
                        rows:{},
                        columns: {
                            data: {
                                id: {
                                    id: "id",
                                    data: "id",
                                    width: 50,
                                    header: {
                                        content: "ID"
                                    }
                                },
                                label: {
                                    id: "label",
                                    data: "label",
                                    width: 200,
                                    header: {
                                        content: "Label"
                                    }
                                }
                            }
                        }
                    },
                    chart: {
                        spacing: 1,
                        items:{},
                        time: {
                            period: 'day',
                            from:new Date('2020-04-01').getTime(),
                            to:new Date('2020-04-06').getTime(),
                        }
                    }
                },



                    form: {
                        time: '',
                        description: '',
                        task: null,
                        date: null,
                        project_id: this.$route.params.id,
                        member_id: this.$auth.user().id
                    },
                    tasks: [],

                }
            },
        mounted(){
            this.getTasks();
            this.getTimes();
        },

        methods:{
            getTimes(){
                this.axios({
                    method: 'get',
                    url: laroute.route('times', {project:this.$route.params.id,
                    member:this.$auth.user().id}),

                }).then((response) => {
                    let records = response.data.data;
                    let first = response.data.first_day;
                    let last = response.data.last_day;
                    this.data.chart.time.from = new Date(first).getTime();
                    this.data.chart.time.to = new Date(last).getTime();


                    let rows= {};
                    let items = {};
                    for (let time of response.data.data){

                        rows[time.task_id ? time.task_id : 0] = {
                            id:time.task_id ? time.task_id : 0,
                            label: time.task ?  time.task.name : 'Project',
                        };
                        items[time.id] = {
                            id:time.id,
                            label: time.time,
                            time: {
                                start:new Date(time.date).getTime(),
                                end: new Date(time.date).getTime(),
                            },
                            rowId: time.task_id ? time.task_id : 0,
                            style:{
                                background:'#67FFC8'
                            }
                        };
                    }

                    this.data.list.rows = rows;
                    this.data.chart.items = items;

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
                this.axios({
                    method: 'post',
                    url: laroute.route('times.create', {}),
                    data:this.form
                }).then((response) => {
                    this.getTimes();
                })
                    .catch((error) => console.log(error))
            },
            beforeDestroy() {
                subs.forEach(unsub => unsub());
            },
            onState(state) {
                this.state = state;
            }
        }
    }
</script>
<style scoped>
    .picker{
        width: 100% !important;
    }
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
<style>
    .gantt-elastic__main-view{
        background: #2c2e38 !important;
        color: white !important;
    }
    .gantt-elastic__main-container-wrapper{
        border-color:white !important;
        color: white !important;
    }
    .gantt-elastic__chart-row-bar-polygon.gantt-elastic__chart-row-project-polygon{
        stroke:#67FFC8 !important;
        fill:#67FFC8 !important;
    }
    .gantt-elastic__chart-row-text{
        background:#67FFC8 !important;
    }
    .gantt-elastic__chart-days-highlight-rect{
        fill:#373a44 !important;
        color: white !important;
    }
    .gantt-elastic__task-list-header-column{
        background:#2c2e38 !important;
        color: white !important;
    }
    .gantt-elastic__calendar-row-rect.gantt-elastic__calendar-row-rect--month{
        background:#2c2e38 !important;
        color: white !important;
    }
    .gantt-elastic__calendar-row-text.gantt-elastic__calendar-row-text--month{
        color: white !important;
    }
    .gantt-elastic__task-list-item-value{
        background:#2c2e38 !important;
        color: white !important;
    }
    .gantt-elastic__calendar-row-rect.gantt-elastic__calendar-row-rect--day{
        background:#2c2e38 !important;
        color: white !important;
    }
    .gantt-elastic__calendar-row-text.gantt-elastic__calendar-row-text--day{
        color: white !important;
    }
    .gantt-elastic__chart-days-highlight-rect{
        fill:#2c2e38 !important;
    }
    .gantt-elastic__grid-line-vertical{
        stroke:white !important;
    }
    .gantt-elastic__grid-line-horizontal{
        stroke:white !important;
    }
    .gantt-elastic__grid-line-time{
        stroke:transparent !important;
    }
</style>