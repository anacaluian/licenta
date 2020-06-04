<?php

namespace App\Providers;

use App\Comment;
use App\File;
use function Couchbase\defaultDecoder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class CommentServiceProvider
{
    protected $commentModel;

    public function __construct(Comment $comment)
    {
        $this->commentModel = $comment;
    }

    public function index($project, $task){
        $comments = $this->commentModel->where('project_id',$project)->where('task_id',$task)->with('member')->with('files')->orderByDesc('id')->get();

        foreach ($comments as $comment) {
            $date = Carbon::parse($comment->updated_at);
            $now = Carbon::now();
            $diff = $date->diffInDays($now);
            if ($diff == 0){
                $diff = $date->diffInHours($now);
                $comment->last_edit = $diff . ' hours ago';
            }else{
                $comment->last_edit = $diff . ' days ago';
            }
        }

        return[
            'status' => 'success',
            'data' => $comments
        ];
    }

    public function create(array $data){
        $comment = new Comment();
        $comment->user_id = $data['user_id'];
        $comment->project_id = $data['project_id'];
        $comment->task_id = $data['task_id'];
        $comment->comment = $data['comment'];
        if ($comment->save()){
            if (array_key_exists('files',$data) ){
               $upload = $this->upload($comment->id,$data['project_id'],$data['files']);
                if($upload) {
                    return response()->json('success',200);
                }
            }
        }
        return response()->json('error',500);

    }

    public function upload($comment_id,$project_id,$files){
//        $comment = $this->commentModel->where('id',$comment_id)->pluck('files')->first();
//        $new_files = [];
       foreach ($files as $file){
           $path = Storage::putFile('files',$file);
           $file = new File();
           $file->comment_id = $comment_id;
           $file->project_id = $project_id;
           $file->file_path = $path;
           $file->save();
//           if ($comment){
//               $new_files = json_decode($comment);
//               array_push($new_files,$path);
//           }
//           else{
//               array_push($new_files,$path);
//           }
       }
//        $comment = $this->commentModel->where('id',$comment_id)->update([
//            'files' => json_encode($new_files)
//            ]);
//       if ($comment){
//           return 1;
//       }
//        return 0;
//
     }
}
