<template>
    <div>
        <div>
            <label >
                <span class=" btn  btn-secondary add-files"><i class="fas fa-plus"></i> New File </span>
                <input @change="addFile" type="file" style="display: none" ref="files" class="form-control-file"  multiple="multiple">
            </label>
        </div>
        <div class="mt-3"  v-for="(file,index) in files">
            <h5 class="text-white">{{index}}</h5>
            <b-table class="table-sm" :dark="true"  hover :items="files[index]" :fields="fields">
                <template v-slot:cell(download)="row">
                    <b-button @click="download(row.item.id,row.item.file_name)" class="add-files"> <i class="fas fa-download"></i> Download</b-button>
                </template>
                <template v-slot:cell(by)="row">
                    {{row.item.owner.first_name}}  {{row.item.owner.last_name[0]}}.
                </template>
            </b-table>

        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                files:[],
                fields:[
                    {
                        key:'file_name',
                        label:'File'
                    },
                    {
                        key:'by',
                        label:'By'
                    },
                    {
                        key:'download',
                        label:'Download'
                    },
                ]
            }
        },
        mounted(){
            this.getFiles();
        },
        methods:{
            getFiles(){
                this.axios({
                    method: 'get',
                    url: laroute.route('files', {project: this.$route.params.id}),
                }).then((response) => {
                    this.files = response.data.data;
                })
                    .catch((error) => console.log(error))
            },
            addFile(){
                let formData = new FormData();
                for( var i = 0; i < this.$refs.files.files.length; i++ ){
                    let file = this.$refs.files.files[i];
                    formData.append('files[' + i + ']', file);
                }
                formData.append('project', this.$route.params.id);
                formData.append('owner', this.$auth.user().id);
                this.axios({
                    method: 'post',
                    url: laroute.route('files.create', {}),
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    data: formData
                }).then((response) => {
                })
                    .catch((error) => console.log(error))
            },
            download(id,file_name){
                this.axios({
                    method: 'get',
                    url: laroute.route('files.download', {file:id}),
                    responseType: 'blob'
                }).then((response) => {
                    console.log(response.data)
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', file_name);
                    document.body.appendChild(link);
                    link.click();
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style scoped>
    .add-files{
        border-radius: 16px;
        background-color: #67FFC8 !important;
    }
</style>