<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use App\Models\Contact;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
        $status = $request->input('status');

        $messages = Contact::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('tell', 'LIKE', "%{$query}%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->paginate(4);

        if ($request->ajax()) {
            return view('admin.contacts.partials.messages_list', compact('messages'))->render();
        }

        return view('admin.contacts.index', compact('messages'));
    }

    public function store(ContactRequest $request)
    {
        $this->contactService->handleContactSubmission($request->validated());
        return back()->with('success', 'បង្កើតសារត្រូវបានជោគជ័យ!');
    }

    public function update(Request $request, $id)
    {
        $this->contactService->updateStatus($id, $request->status);
        return back()->with('success', 'ស្ថានភាពសារត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
    }

    public function destroy($id)
    {
        $this->contactService->deleteMessage($id);
        return back()->with('success', 'សារត្រូវបានលុបចេញដោយជោគជ័យ!');
    }
}
