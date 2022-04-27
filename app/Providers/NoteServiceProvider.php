<?php

namespace App\Providers;

use App\Note;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class NoteServiceProvider extends ServiceProvider
{
    protected $noteModel;

    public function __construct(Note $note)
    {
        $this->noteModel = $note;
    }

    public function index($project){
        $notes = $this->noteModel->where('project_id',$project)->with('author')->get();

        foreach ($notes as $note) {
            $note->content = htmlspecialchars_decode($note->content);
            $date = Carbon::parse($note->updated_at);
            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            if ($diff == 0){
                $diff = $date->diffInHours($now);
                $note->last_edit = $diff . ' hours ago';
            }else{
                $note->last_edit = $diff . ' days ago';
            }
        }

        return [
            'status' => 'success',
            'data' => $notes
        ];
    }

    public function create(array $data){
        $note = new Note();
        $note->project_id = $data['project_id'];
        $note->created_by = $data['author'];
        if($note->save()){
            return response()->json('success', 200);
        }
        return response()->json('error', 500);
    }

    public function update(array $data){
       $update = $this->noteModel->where('id',$data['note_id'])->update([
           'title' => $data['title'],
           'content' => $data['content']
       ]);
       if ($update){
           return response()->json('success', 200);
       }
        return response()->json('error', 500);
    }

    public function delete($id){
        $delete = $this->noteModel->find($id)->delete();
        if ($delete){
            return response()->json('success', 200);
        }
        return response()->json('error', 500);
    }

}
