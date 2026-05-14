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
        $contacts = $allSettings->where('key', '!=', 'map_link');

        return view('frontend.contact', compact('contacts', 'mapData'));
    }

    public function store(ContactRequest $request)
    {
        try {
            $data = $request->validated();

            $data['description'] = clean($request->description);
            // $data['description'] = Purifier::clean($request->description);
            // $data['description'] = strip_tags($request->description, '<p><br><b><strong><ul><li>');
            // $data['description'] = strip_tags($request->description, '<p><br><b><strong><ul><li><ol><h1><h2><h3>');

            $data['status'] = 'unread';

            $this->contactService->handleContactSubmission($data);

            return back()->with('success', 'សាររបស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
    }
}
