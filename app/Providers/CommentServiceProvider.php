<?php

namespace App\Providers;

use App\Comment;
use App\File;
use App\User;
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
               $upload = $this->upload($comment->id,$data['user_id'],$data['project_id'],$data['files']);
                if($upload) {
                    return response()->json('success',200);
                }
            }
            activity()
                ->causedBy( $data['user_id'])
                ->performedOn($comment)
                ->withProperties(['key' => 'comment'])
                ->createdAt(now())
                ->log('User commented on Task #' .  $data['task_id']);
        }
        return response()->json('error',500);

    }

    public function upload($comment_id,$owner,$project_id,$files)
    {
        foreach ($files as $file) {
            $file_name = $file->getClientOriginalName();
            $path = Storage::disk('public')->putFile('files', $file);
            $file = new File();
            $file->owner = $owner;
            $file->comment_id = $comment_id;
            $file->project_id = $project_id;
            $file->file_name = $file_name;
            $file->file_path = Storage::url($path);
            $file->save();

        }
    }
}
