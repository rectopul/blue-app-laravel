<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public $route = 'admin.task';

    public function index()
    {
        $tasks = Task::orderBy('sort_order')->orderBy('id')->get();
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
            'watch_seconds' => 'required|integer|min:5|max:3600',
            'sort_order' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:100',
        ]);

        if ($request->id) {
            $model = Task::findOrFail($request->id);
        } else {
            $model = new Task();
            $model->remaining_code = '999';
        }

        $model->title = $request->title;
        $model->description = $request->description;
        $model->video_url = $request->video_url;
        $model->watch_seconds = $request->watch_seconds;
        $model->sort_order = $request->sort_order ?? 0;
        $model->icon = $request->icon ?: 'play_circle';
        $model->is_active = $request->is_active ?? true;
        $model->save();

        return redirect()->route($this->route . '.index')->with('success', $request->id ? 'Task Updated Successful.' : 'Task Created Successful.');
    }

    public function delete($id)
    {
        $model = Task::find($id);
        $model->delete();
        return redirect()->route($this->route . '.index')->with('success', 'Item Deleted Successful.');
    }
}
