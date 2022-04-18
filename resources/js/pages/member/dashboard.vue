<template>
    <div class="container-fluid ml-5 mr-5 mt-3">
        <div class="row ml-1">
            <h6 class="ap"><strong>Active Projects</strong></h6>
        </div>
        <div class="row ml-2 mt-4 mr-5">
            <div class="card-group">
                <b-card
                        v-for="project in projects"
                        :key="project.id"
                        :title="project.name"
                        style="max-width: 20rem;min-height: 100%;"
                        class="mb-2 mr-4 project-card shadow-lg p-3 mb-5 bg-white rounded"
                        @click="$router.push({ name: 'project', params: { id: project.id } })"
                >
                    <b-card-text>
                      <div class="row">
                          <p>for <strong>{{project.owner}}</strong></p>
                      </div>
                        <div class="row">
                            <b-badge pill variant="success">{{project.state.replace(/_/g,' ')}}</b-badge>
                        </div>
                    </b-card-text>
                </b-card>
            </div>
        </div>
    </div>
</template>
<script>  export default {
    data() {
        return {
            projects:[]
        }
    },
    mounted(){
        this.getProjects();
    },
    methods:{
        getProjects(){
            this.axios({
                method: 'get',
                url: laroute.route('projects', { member:this.$auth.user().id, role:this.$auth.user().roles}),
            }).then((response) => {
               this.projects = response.data.data;
            })
                .catch((error) => console.log(error))
        }
    }
}
</script>
<style scoped>
    .ap{
        color: white;
    }
    .card-title{
        font-weight:bold;
        color: #B9B9B9;
    }
    .card-text{
        color: #B9B9B9;
    }
    .project-card{
        background-color: #373a44 !important;
        border-radius: 12px;

    }
    .badge-success{
        bottom: 10%;
        position: absolute;
        text-transform: capitalize;
    }
</style>
