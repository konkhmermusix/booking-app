<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest; // ប្រើ Request ដែលយើងបានបង្កើត
use App\Services\ContactService;    // ប្រើ Service សម្រាប់ Business Logic
use Illuminate\Http\Request;
use App\Models\ContactSetting;

class ContactWebController extends Controller
{
    protected $contactService;

    // Inject ContactService ចូលមកក្នុង Controller
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    // បង្ហាញទំព័រ Contact Form (UI)

    public function index()
    {
        $allSettings = ContactSetting::where('status', true)->get();

        $mapData = $allSettings->where('key', 'map_link')->first();
        $contacts = $allSettings->where('key', '!=', 'map_link');

        return view('frontend.contact', compact('contacts', 'mapData'));
    }

    // ទទួលទិន្នន័យពី Customer និងរក្សាទុក (Create)
    public function store(ContactRequest $request)
    {
        try {
            $data = $request->validated();

            // កំណត់ Status ដំបូងឱ្យត្រូវតាម Enum ('unread')
            $data['status'] = 'unread';

            // រក្សាទុកទិន្នន័យ
            $this->contactService->handleContactSubmission($data);

            // បញ្ជូនសារជោគជ័យទៅកាន់ Frontend
            return back()->with('success', 'សាររបស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ!');
        } catch (\Exception $e) {
            // ប្រសិនបើមានបញ្ហា (ឧទាហរណ៍ DB Error)
            return back()->withInput()->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
    }
}
