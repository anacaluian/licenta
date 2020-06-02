<template>
    <div class="row ml-2">
        <b-card
                v-for="list in lists"
                :title="title"
                tag="article"
                style="max-width: 20rem;"
                class="mb-2 card col-md-2 mr-3"
        >
            <b-card-text>
                <draggable class="list-group"  group="people" :list="list" @change="log">
                    <div
                            class="list-group-item"
                            v-for="(element, index) in list"
                            :key="element.name"
                    >
                        {{ element.name }} {{ index }}
                    </div>
                </draggable>
            </b-card-text>
            <b-card-footer>
                <i class="fas fa-plus"></i>
                Add task
            </b-card-footer>
        </b-card>
    </div>
</template>
<script>
    import draggable from 'vuedraggable'
    export default {
    components: {
        draggable,
    },
    props: {
        title: String,
        lists:Object,
    },
    data(){
        return{

        }
    },
    methods:{
        log: function(evt) {
            window.console.log(evt);
        }
    }
    }
</script>
<style scoped>
    .card-body{
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0;
    }
    .card-title{
        margin-left: 10px;
        font-weight: bold;
        color:#CFCFD0;
    }
    .card-footer{
        color:#67FFC8;
        font-weight: bold;
        background-color: transparent;
    }
    .card{
        border-radius: 10px;
        background-color: #373a44;
        height: fit-content;
    }
    .list-group{
        border-radius: 0;
    }
    .list-group-item{
        background-color: #373a44;
        color:#CFCFD0;
    }
</style>