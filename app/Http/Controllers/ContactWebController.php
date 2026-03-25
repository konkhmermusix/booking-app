<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest; // ប្រើ Request ដែលយើងបានបង្កើត
use App\Services\ContactService;    // ប្រើ Service សម្រាប់ Business Logic
use Illuminate\Http\Request;

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
        return view('frontend.contact');
    }

    // ទទួលទិន្នន័យពី Customer និងរក្សាទុក (Create)
    public function store(ContactRequest $request)
    {
        try {
            // ហៅ Service ឱ្យចាត់ចែងការរក្សាទុកទិន្នន័យ
            $this->contactService->handleContactSubmission($request->validated());

            // បញ្ជូនសារទៅកាន់ទំព័រដើមវិញជាមួយ Success Message
            return back()->with('success', 'សាររបស់អ្នកត្រូវបានបញ្ជូនដោយជោគជ័យ! យើងនឹងទាក់ទងទៅវិញឆាប់ៗ។');
        } catch (\Exception $e) {
            // បង្ហាញសារ Error ប្រសិនបើមានបញ្ហាបច្ចេកទេស
            return back()->with('error', 'សូមអភ័យទោស! មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
    }
}
