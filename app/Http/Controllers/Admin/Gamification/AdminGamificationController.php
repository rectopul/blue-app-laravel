<?php

namespace App\Http\Controllers\Admin\Gamification;

use App\Http\Controllers\Controller;
use App\Modules\Gamification\Models\GamificationSetting;
use Illuminate\Http\Request;

class AdminGamificationController extends Controller
{
    public function index()
    {
        $settings = GamificationSetting::orderBy('required_referrals')->get();
        return view('admin.pages.gamification.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.pages.gamification.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'required_referrals' => 'required|integer',
            'page_name' => 'required|string',
            'bonus_reward' => 'required|numeric',
        ]);

        GamificationSetting::create($request->all());

        return redirect()->route('admin.gamification.index')->with('success', 'Configuração de bônus criada.');
    }

    public function edit($id)
    {
        $setting = GamificationSetting::findOrFail($id);
        return view('admin.pages.gamification.form', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = GamificationSetting::findOrFail($id);
        $setting->update($request->all());

        return redirect()->route('admin.gamification.index')->with('success', 'Configuração de bônus atualizada.');
    }

    public function destroy($id)
    {
        GamificationSetting::destroy($id);
        return redirect()->route('admin.gamification.index')->with('success', 'Configuração removida.');
    }
}
