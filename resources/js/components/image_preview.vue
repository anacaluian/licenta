<template>
    <preview fileType="img" :imgPvProps="data" ></preview>
</template>
<script>
    import preview from '@mrhanson/vue-file-preview'
    export default {
        components: {
            preview
        },

        data(){
            return{
                data:{
                    previewSrcList:[
                    ],
                    coverList:[
                    ]
                },
            }
        },
        props: {
            files: Array,
        },
        mounted(){
            this.manageFiles();
        },methods:{
            manageFiles(){
                for(let image of this.files){
                    if (image.file_path.includes('jpeg') || image.file_path.includes('png') || image.file_path.includes('jpg') || image.file_path.includes('gif'))
                    this.data.coverList.push({url:image.file_path});
                    this.data.previewSrcList.push(image.file_path)
                }
            }
        }
    }
</script>
<style>
    .el-image > img{
        padding: 4px;
        border-radius: 10px;
    }
    .el-image > img:hover{
        border: 2px solid #67FFC8;
    }
</style>