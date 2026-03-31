<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactSetting::query();

        // មុខងារស្វែងរក (Search by label or value)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('label', 'like', '%' . $request->search . '%')
                    ->orWhere('value', 'like', '%' . $request->search . '%');
            });
        }

        // Filter តាមស្ថានភាព (Status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $settings = $query->orderBy('id', 'asc')->get();

        // ពិនិត្យមើលថាតើជាការហៅតាមរយៈ Ajax/Axios ឬទេ
        if ($request->ajax()) {
            return view('admin.contacts_sett.partials.contacts_sett_list', compact('settings'))->render();
        }

        return view('admin.contacts_sett.index', compact('settings'));
    }

    // បន្ថែមមុខងារបង្កើតថ្មី (Add)
    public function store(Request $request)
    {
        $request->validate([
            'key'    => 'required|unique:contact_settings,key',
            'label'  => 'required|string|max:255',
            'value'  => 'required|string',
            'icon'   => 'nullable|string',
            'color'  => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        ContactSetting::create($request->all());

        return redirect()->back()->with('success', 'បន្ថែមព័ត៌មានទំនាក់ទំនងថ្មីបានជោគជ័យ!');
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'label'  => 'required|string|max:255',
            'value'  => 'required|string',
            'icon'   => 'nullable|string',
            'color'  => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $setting = ContactSetting::findOrFail($id);

        $setting->update([
            'label'  => $request->label,
            'value'  => $request->value,
            'icon'   => $request->icon,
            'color'  => $request->color,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
    }

    /**
     * បន្ថែមមុខងារ Delete (ប្រសិនបើចាំបាច់)
     */
    public function destroy($id)
    {
        $setting = ContactSetting::findOrFail($id);
        $setting->delete();

        return redirect()->back()->with('success', 'លុបព័ត៌មានទំនាក់ទំនងរួចរាល់!');
    }
}
