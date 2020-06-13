<template>
    <div class="container-fluid ml-5" >
        <div class="col-4 mt-4">
            <treeselect
                    v-model="project"
                    :multiple="false"
                    :options="projects"
            ></treeselect>
        </div>
        <GSTC :config="data" @state="onState" />
    </div>
</template>
<script>
    import GSTC from "vue-gantt-schedule-timeline-calendar";
    let subs = [];
    export default {
        components: {
            GSTC
        },
        data(){
            return{
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
                times:[],
                project:null,
                projects:[]

            }
        },
        mounted(){
            this.getTimes();
        },
        watch:{
            project:function () {
                let rows= {};
                let items = {};
                let times = this.times[this.project]
                for (let time of times){
                    rows[time.id] = {
                        id:time.id,
                        label: time.task ?  time.task.name : 'Project',
                    };
                    items[time.id] = {
                        id:time.id,
                        label: time.description,
                        time: {
                            start:new Date(time.date).getTime(),
                            end: new Date(time.date).getTime(),
                        },
                        rowId: time.id,
                    };
                }

                this.data.list.rows = rows;
                this.data.chart.items = items;
            }
        },
        beforeDestroy() {
            subs.forEach(unsub => unsub());
        },
        methods:{
             getTimes(){
            //     let rows= {};
            //     let items = {};
            //     rows[1] = {
            //         id:1,
            //         label: 'Room ',
            //     };
            //     items[1] = {
            //         id:1,
            //         label: 'User id ',
            //         time: {
            //             start:new Date('2020-06-02').getTime(),
            //             end: new Date('2020-06-03').getTime(),
            //         },
            //         rowId: 1,
            //     };

                // this.data.list.rows = rows;
                // this.data.chart.items = items;

                this.axios({
                    method: 'get',
                    url: laroute.route('times', {project:0,
                        member:0}),
                }).then((response) => {
                    this.times = response.data.data;
                    for(let time in this.times){
                       this.projects.push({
                           id:time,
                           label:time
                       })
                        this.project = time
                    }
                    let first = response.data.first_day;
                    let last = response.data.last_day;
                    this.data.chart.time.from = new Date(first).getTime();
                    this.data.chart.time.to = new Date(last).getTime();
                })
                    .catch((error) => console.log(error))
            },
            onState(state) {
                this.state = state;
            }
        }
    }
</script>
<style scoped>

</style>