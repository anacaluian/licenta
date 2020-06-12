<?php

namespace App\Providers;

use App\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    protected $fileModel;

    public function __construct(File $file)
    {
        $this->fileModel = $file;
    }

    public function index($project){
        $files = $this->fileModel->where('project_id',$project)->with('owner')->latest()->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });

        return [
            'status' => 'success',
            'data' => $files
        ];

    }

    public function create(array $data)
    {
        foreach ($data['files'] as $file) {
            $file_name = $file->getClientOriginalName();
            $path = Storage::disk('public')->putFile('files', $file);
            $file = new File();
            $file->owner = $data['owner'];
            $file->project_id =$data['project'];
            $file->file_path = Storage::url($path);
            $file->file_name = $file_name;
            $file->save();

        }

        activity()
            ->causedBy( $data['owner'])
            ->performedOn(new File())
            ->withProperties(['project' => $data['project']])
            ->createdAt(now())
            ->log(Auth::user()->first_name .' '. Auth::user()->last_name . ' added new files.');
    }

    public function download($id)
    {
        $file = $this->fileModel->where('id', $id)->pluck('file_path')->first();
        return response()->download(public_path($file));
    }
}
