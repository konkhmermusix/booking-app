<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\Request;
use App\Models\ContactSetting;
use Mews\Purifier\Facades\Purifier;

class ContactWebController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $allSettings = ContactSetting::where('status', true)->get();

        $mapData = $allSettings->where('key', 'map_link')->first();

        $excludedKeys = ['map_link', 'qr_code_image', 'bank_account_name', 'bank_account_number', 'bank_name'];
        $contacts = $allSettings->reject(function ($item) use ($excludedKeys) {
            $key = strtolower($item->key);
            $label = strtolower($item->label);
            return in_array($key, $excludedKeys)
                || str_starts_with($key, 'qr_')
                || str_starts_with($key, 'bank_')
                || str_contains($label, 'qr')
                || str_contains($label, 'ធនាគារ');
        });

        return view('frontend.contact', compact('contacts', 'mapData'));
    }

    public function store(ContactRequest $request)
    {
        try {
            $data = $request->validated();

            $data['description'] = strip_tags(trim($request->description));
            $data['status'] = 'unread';

            $this->contactService->handleContactSubmission($data);

            return back()->with('success', 'សាររបស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
    }
}
