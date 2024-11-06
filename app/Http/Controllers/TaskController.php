<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function task(){
        return view('task.index');
    }

    public function task_store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'deadline' => 'required',
            'description' => 'required',
            'file' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['res'=>'Input field blank!']);
        }


        $file = $request->file('file');
        $file_name = uniqid().'.'.$file->getClientOriginalExtension();
        Task::create([
            'task_title' => $request->title,
            'task_deadline' => $request->deadline,
            'task_description' => $request->description,
            'task_pdf' => $file_name,
            'task_assignee' => Auth::user()->id,
        ]);

        $file->storeAs('pdf', $file_name, 'public');
        // $file->move(public_path('pdf') . $file_name);


        return response()->json(['res'=>'Task Created Successfully!']);
    }

    public function gettask(){
        $tasks = Task::all();
        return response()->json(['tasks'=>$tasks]);
    }

    public function deltetask($id){
        $task = Task::find($id);
        unlink(public_path('storage/pdf/'.$task->task_pdf));
        $task->delete();
        return response()->json(['success'=>'Task deleted successfully!']);
    }

    public function make_done($id){
        Task::find($id)->update([
            'task_status' => 1,
        ]);
        return response()->json(['done'=>'Task Completed successfully!']);
    }

    public function accept($id){
        Task::find($id)->update([
            'task_status' => 2,
        ]);
        return response()->json(['accept'=>'Task Accepted successfully!']);
    }

    public function reject($id){
        Task::find($id)->update([
            'task_status' => 0
        ]);
        return response()->json(['reject'=>'Task Rejected successfully!']);
    }
}

