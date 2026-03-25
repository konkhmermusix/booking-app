<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Services\ContactService;

class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    // [READ] បង្ហាញបញ្ជីសារ (សម្រាប់ Admin)
    public function index()
    {
        $messages = $this->contactService->listAllMessages();
        return view('admin.contacts.index', compact('messages'));
    }

    // [CREATE] រក្សាទុកសារពី Form (សម្រាប់ User)
    public function store(ContactRequest $request)
    {
        $this->contactService->handleContactSubmission($request->validated());
        return back()->with('success', 'សារត្រូវបានបញ្ជូន!');
    }

    // [UPDATE] កែប្រែស្ថានភាពសារ (Status)
    public function update(Request $request, $id)
    {
        $this->contactService->updateStatus($id, $request->status);
        return back()->with('success', 'ស្ថានភាពសារត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
    }

    // [DELETE] លុបសារ
    public function destroy($id)
    {
        $this->contactService->deleteMessage($id);
        return back()->with('success', 'សារត្រូវបានលុបចេញ!');
    }
}
