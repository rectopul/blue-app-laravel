<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public $route = 'admin.package';
    public function index()
    {
        $packages = Package::get();
        return view('admin.pages.package.index', compact('packages'));
    }
    public function create($id = null)
    {
        $data = null;
        if ($id) {
            $data = Package::find($id);
        }
        return view('admin.pages.package.insert', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'exists:packages,id'],
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'validity' => ['required', 'integer', 'min:1'], // dias
            'commission_with_avg_amount' => ['required', 'numeric', 'min:0'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $package = Package::find($request->id);

        if (!$package) {
            $package = new Package();
            $package->status = 'active';
        }

        $package->name = $validated['name'];
        $package->title = $validated['title'];
        $package->price = $validated['price'];
        $package->validity = $validated['validity'];
        $package->commission_with_avg_amount = $validated['commission_with_avg_amount'];

        if ($request->filled('status')) {
            $package->status = $validated['status'];
        }

        // ✅ Upload/Update da imagem
        if ($request->hasFile('photo')) {
            // apaga antiga (se existir e se você salvar no storage)
            if ($package->photo && Storage::disk('public')->exists($package->photo)) {
                Storage::disk('public')->delete($package->photo);
            }

            $path = $request->file('photo')->store('packages', 'public');
            $package->photo = $path; // ex: packages/arquivo.webp
        }

        $package->save();

        return redirect()
            ->route('admin.package.index')
            ->with('success', $request->id ? 'Pacote atualizado com sucesso!' : 'Pacote criado com sucesso!');
    }



    public function insert_or_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'price' => 'required|numeric',
            'validity' => 'required|numeric',
            'commission_with_avg_amount' => 'required|numeric',
        ]);
        if ($request->id) {
            $model = Package::findOrFail($request->id);
            $model->status = $request->status;
        } else {
            $model = new Package();
        }

        $percent_total_return = $request->validity * $request->commission_with_avg_amount;
        $total_return_amount = $request->price * ($percent_total_return / 100);
        $path = uploadImage(false, $request, 'photo', 'upload/package/', 200, 200, $model->photo);
        $model->photo = $path ?? $model->photo;
        $model->name = $request->name;
        $model->title = $request->title;
        $model->price = $request->price;
        $model->validity = $request->validity;
        $model->commission_with_avg_amount = $request->commission_with_avg_amount;
        $model->total_return_amount = $total_return_amount;
        $model->total_return_percent = $percent_total_return;
        $model->save();
        return redirect()->route($this->route . '.index')->with('success', $request->id ? 'Package Updated Successful.' : 'Package Created Successful.');
    }

    public function delete($id)
    {
        $model = Package::find($id);
        $model->update([
            'status' => 'inactive'
        ]);
        return redirect()->route($this->route . '.index')->with('success', 'Item Dactived Successful.');
    }
}
