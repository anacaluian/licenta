<template>
    <div>
        <div>
            <b-button @click="addNote" class="add-notes"><i class="fas fa-plus"></i> New Note</b-button>
        </div>
        <div class="row ml-2 mt-4">
            <b-card
                    v-for="(note, index) in notes"
                    :key="index"
                    tag="article"
                    style="width:11%;"
                    aria-controls="note-preview"
                    aria-expanded="true"
                    @click="showNoteDetails(note)"
                    class="mb-2 card  mr-3 note"
            >
                <h5 class="card-title" v-html="note.title"></h5>
                <b-card-text>
                    <p> <small>by {{note.author.first_name +' '+ note.author.last_name }}</small></p>
                    <p v-html="note.content.slice(0,50)">...</p>
                </b-card-text>
                <b-card-footer>
                        <p>
                            <small>
                                <strong>
                                    Active {{note.last_edit}}
                                </strong>
                            </small>
                        </p>
                </b-card-footer>
            </b-card>
        </div>
        <div class="sidebar">
            <b-sidebar
                    id="note-preview"
                    v-model="visible"
                    bg-variant="dark"
                    :no-header-close="true"
                    text-variant="light"
                    :no-close-on-esc="true"
            >
                <b-button class="shadow-lg" id="close-btn" @click="closeTaskDetails"><i class="fas fa-times"></i></b-button>
                <div class="sidebar-content">
                    <div class="note-content">
                        <div class="row">
                            <editor-content class="ml-4 text-dark h3 col-10" :editor="title" />
                            <span @click="deleteNote(selectedNote.id)"><i class="fas fa-trash-alt"></i></span>
                        </div>
                        <editor-content class="ml-4 mt-4 text-dark" :editor="content" />
                    </div>
                </div>
            </b-sidebar>
        </div>
    </div>
</template>
<script>
    import { Editor, EditorContent, EditorMenuBar  } from 'tiptap';
    export default {
        components: {
            EditorContent,
            EditorMenuBar,
        },
        data(){
            return{
                notes:[],
                selectedNote:'',
                visible:false,
                title:null,
                content:null
            }
        },
        mounted(){
            this.getNotes();
            this.title = new Editor({
                content: '',
            });
            this.content = new Editor({
                content: '',
            })
        },
        methods:{
            getNotes(){
                this.axios({
                    method: 'get',
                    url: laroute.route('notes', {project:this.$route.params.id}),
                }).then((response) => {
                   this.notes = response.data.data;
                })
                    .catch((error) => console.log(error))
            },
            addNote(){
                this.axios({
                    method: 'post',
                    url: laroute.route('notes.create', {}),
                    data:{
                        project_id:this.$route.params.id,
                        author:this.$auth.user().id
                    }
                }).then((response) => {
                    this.getNotes();
                })
                    .catch((error) => console.log(error))
            },
            showNoteDetails(note){
                this.visible = true;
                this.title.setContent(note.title);
                this.content.setContent( note.content);
                this.selectedNote = note;

            },
            closeTaskDetails(){
                this.visible = false;
                this.axios({
                    method: 'post',
                    url: laroute.route('notes.update', {}),
                    data:{
                        note_id:this.selectedNote.id,
                        title:this.title.getHTML(),
                        content:this.content.getHTML()
                    }
                }).then((response) => {
                    this.getNotes();
                })
                    .catch((error) => console.log(error))
            },
            deleteNote(id){
                this.axios({
                    method: 'post',
                    url: laroute.route('notes.delete', {}),
                    data:{
                        id:id
                    }
                }).then((response) => {
                    this.visible = false;
                    this.getNotes();
                })
                    .catch((error) => console.log(error))
            }
        }
    }
</script>
<style scoped>
    i{
        color:#67FFC8 !important ;
    }
    .card-body{
        margin-bottom: 15%;
    }
    .add-notes {
        border-radius: 16px;
        background-color: #67FFC8 !important;

    }
    .note{
        background-color: #FFF0CE;
        border-radius: 10px;

    }
    .card-footer{
        background-color: transparent !important;
        border-top:none !important;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 23%;
    }
    .sidebar {
        position: fixed;
        right: 0;
        top: 15px;
        width: fit-content;
        min-width: 50%;
        z-index: 200;
    }

    .sidebar-content {
        height: 95vh !important;
        margin-left: 20px;
    }
    #close-btn > i{
       color: #67FFC8 !important;
    }
    .note-content{
        position: fixed;
        padding-top: 2%;
        margin: 0;
        top: 5%;
        left: 51.5%;
        width: 32%;
        height: 88%;
        border-radius: 16px;
        background-color: #FFF0CE;
    }
</style>
<style>
    #note-preview{
        border-bottom-left-radius: 16px;
        border-top-left-radius: 16px;
    }
</style>