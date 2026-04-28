<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    // ត្រឹមត្រូវ
    public function index()
    {
        $settings = ContactSetting::latest()->paginate(5);
        return view('admin.contacts_sett.index', compact('settings'));
    }

    // រក្សាទុកទិន្នន័យថ្មី
    public function store(Request $request)
    {
        $data = $request->validate([
            'key'    => 'required|unique:contact_settings,key',
            'label'  => 'required',
            'value'  => 'required',
            'icon'   => 'nullable',
            'color'  => 'nullable',
            'status' => 'boolean'
        ]);

        ContactSetting::create($data);
        return back()->with('success', 'បន្ថែមបានជោគជ័យ!');
    }

    // កែប្រែទិន្នន័យ
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required',
            'value' => 'required',
        ]);

        $setting = ContactSetting::findOrFail($id);

        $setting->update([
            'key' => $request->key,
            'label'  => $request->label,
            'value'  => $request->value,
            'icon'   => $request->icon,
            'color'  => $request->color,
            'status' => $request->has('status') ? 1 : 0, // បើ checkbox មិនបាន check វាផ្ញើមក null
        ]);

        return redirect()->back()->with('success', 'កែសម្រួលជោគជ័យ!');
    }

    public function destroy($id)
    {
        $setting = ContactSetting::findOrFail($id);
        $setting->delete();

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានលុបជោគជ័យ!');
    }
}
