<template>
    <div class="container-fluid ml-5 no-scroll" >
        <GSTC class="shadow-lg" :config="data" @state="onState" />
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
                                label: {
                                    id: "label",
                                    data: "label",
                                    width: 200,
                                    header: {
                                        content: "Project"
                                    }
                                },
                                total: {
                                    id: "total",
                                    data: "total",
                                    width: 200,
                                    header: {
                                        content: "Total"
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
            }
        },
        mounted(){
            // this.getTimes();
            this.getMonth();
        },
        beforeDestroy() {
            subs.forEach(unsub => unsub());
        },
        methods:{
            getMonth(){
                this.axios({
                    method: 'get',
                    url: laroute.route('times.month', {}),
                }).then((response) => {
                    let rows= {};
                    let items = {};
                    let first = response.data.first_day;
                    let last = response.data.last_day;
                    this.data.chart.time.from = new Date(first).getTime();
                    this.data.chart.time.to = new Date(last).getTime();
                    for (let[index,project] of response.data.data.entries()){
                            rows[index] = {
                                id:index,
                                label: project.name,
                                total:project.total + '€',
                            };
                            items[index] = {
                                id:index,
                                label:project.hours +' Hours ' +  project.minutes + ' Minutes',
                                time: {
                                    start:new Date(project.from).getTime(),
                                    end: new Date(project.to).getTime(),
                                },
                                rowId: index,
                                style:{
                                    background:'#67FFC8'
                                }
                            };
                        this.data.list.rows = rows;
                        this.data.chart.items = items;
                    }
                })
                    .catch((error) => console.log(error))
            },
            onState(state) {
                this.state = state;
            }
        }
    }
</script>
<style>
    .no-scroll{
        width: 96%;
    }
    .gantt-schedule-timeline-calendar{
        background-color: #2c2e38 !important;
        color: #CFCFD0 !important;
        -webkit-border-radius: 16px;
        -moz-border-radius: 16px;
        border-radius: 16px;
    }
    .gantt-schedule-timeline-calendar__list-column-header-resizer{
        background: #373a44 !important;
        color: #67FFC8;
    }
    .gantt-schedule-timeline-calendar__list-column-header-resizer{
        background: #373a44 !important;
        color: #67FFC8;
    }
    .gantt-schedule-timeline-calendar__chart-calendar-date.gantt-schedule-timeline-calendar__chart-calendar-date--month.gantt-schedule-timeline-calendar__chart-calendar-date--level-0.gstc-current{
        background: #373a44 !important;
        color: #67FFC8;
    }
    .gantt-schedule-timeline-calendar__chart-calendar-date.gantt-schedule-timeline-calendar__chart-calendar-date--day.gantt-schedule-timeline-calendar__chart-calendar-date--level-1{
        background: #373a44 !important;
        color: #67FFC8;
    }
    .gantt-schedule-timeline-calendar__list-toggle{
        background: #373a44 !important;
    }
</style>