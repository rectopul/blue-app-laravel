<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public $route = 'admin.setting';
    public function index()
    {
        $data = Setting::find(1);
        return view('admin.pages.setting.index', compact('data'));
    }

    public function insert_or_update(Request $request)
    {
        $model = Setting::findOrFail(1);
        $model->withdraw_charge = $request->withdraw_charge;
        $model->minimum_withdraw = $request->minimum_withdraw;
        $model->maximum_withdraw = $request->maximum_withdraw;
        $model->w_time_status = $request->w_time_status;
        $model->free_task_video_url = $request->free_task_video_url;
        $model->free_task_reward = $request->free_task_reward;
        $model->free_task_seconds = $request->free_task_seconds;
        $model->registration_bonus = $request->registration_bonus;
        $model->total_member_register_reword_amount = $request->total_member_register_reword_amount;
        $model->total_member_register_reword = $request->total_member_register_reword;

        $model->active_gateway = $request->active_gateway;
        $model->bitflow_client_id = $request->bitflow_client_id;
        $model->bitflow_client_secret = $request->bitflow_client_secret;
        $model->bitflow_public_key = $request->bitflow_public_key;

        $model->update();
        return redirect()->route($this->route.'.index')->with('success', 'Settings Updated Successfully.');
    }
}
