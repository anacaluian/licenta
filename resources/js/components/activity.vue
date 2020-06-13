<template>
    <div>
        <div v-if="activity.length">
            <timeline  :points="activity"></timeline>
        </div>
        <div v-else>
            <h5 class="text-white">No activity today.</h5>
        </div>

    </div>
</template>
<script>
    import timeline from 'vue-timeline/src/components/timeline.vue'
    export default {
        components: {
            timeline
        },
        data(){
            return{
                activity:[]
            }
        },
        mounted(){
            this.getActivity();
        },
        methods:{
            getActivity(){
                this.axios({
                    method: 'get',
                    url: laroute.route('activities', {project_id:this.$route.params.id}),
                }).then((response) => {
                    this.activity = []
                  for (let object in response.data.data){
                      let obj = response.data.data[object];
                      this.activity.push({
                          pointColor:'red',
                          text: obj.description,
                          date: obj.date
                      })
                  }
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style>
.date{
    color: white !important;
}
.timeline-img.red[data-v-7bff488f]{
    background:  #67FFC8 !important;
    width: 2%;
    height: 33%;
    left: 51.6%;
}

.timeline-content[data-v-7bff488f]{
    padding: 0.1em !important;
    border-radius: 50px;
    top: -34%;
}
</style>