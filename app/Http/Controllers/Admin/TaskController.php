<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Improvment;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public $route = 'admin.task';
    public function index()
    {
        $tasks = Task::all();
        return view('admin.pages.task.index', compact('tasks'));
    }

    public function create($id = null)
    {
        $data = null;
        if ($id) {
            $data = Task::find($id);
        }
        return view('admin.pages.task.insert', compact('data'));
    }

    public function insert_or_update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'video_url' => 'required',
        ]);

        if ($request->id) {
            $model = Task::findOrFail($request->id);
        } else {
            $model = new Task();
            $model->remaining_code = '999';
        }
        $model->title = $request->title;
        $model->video_url = $request->video_url;
        $model->is_active = $request->is_active ?? true;
        $model->save();
        return redirect()->route($this->route . '.index')->with('success', $request->id ? 'Task Updated Successful.' : 'Task Created Successful.');
    }

    public function delete($id)
    {
        $model = Task::find($id);
        $model->delete();
        return redirect()->route($this->route.'.index')->with('success','Item Deleted Successful.');
    }
}
