<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{

    public function index()
    {
        $settings = ContactSetting::latest()->paginate(5);
        return view('admin.contacts_sett.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'    => 'required|unique:contact_settings,key',
            'label'  => 'required',
            'value'  => 'nullable',
            'icon'   => 'nullable',
            'color'  => 'nullable',
            'status' => 'boolean'
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $data['value'] = $file->storeAs('qr', $fileName, 'public');
        }

        if (empty($data['value'])) {
            $data['value'] = 'N/A';
        }

        ContactSetting::create($data);
        return back()->with('success', 'បន្ថែមបានជោគជ័យ');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required',
        ]);

        $setting = ContactSetting::findOrFail($id);
        $value = $request->value;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = time() . '_' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
            $value = $file->storeAs('qr', $fileName, 'public');
        }

        $setting->update([
            'key'    => $request->key,
            'label'  => $request->label,
            'value'  => $value,
            'icon'   => $request->icon,
            'color'  => $request->color,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'កែសម្រួលជោគជ័យ');
    }

    public function destroy($id)
    {
        $setting = ContactSetting::findOrFail($id);
        $setting->delete();

        return redirect()->back()->with('success', 'ទិន្នន័យត្រូវបានលុបជោគជ័យ');
    }
}
