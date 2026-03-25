<?php

namespace App\Services;

use App\Repositories\ContactRepository;

class ContactService
{
    protected $contactRepo;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }

    // សម្រាប់បង្ហាញបញ្ជីសារទាំងអស់ក្នុង Admin
    public function listAllMessages()
    {
        return $this->contactRepo->getAll();
    }

    // សម្រាប់មើលលម្អិត និងប្តូរ Status ទៅជា 'pending' ឬ 'completed'
    public function updateStatus($id, $status)
    {
        return $this->contactRepo->update($id, ['status' => $status]);
    }

    public function deleteMessage($id)
    {
        return $this->contactRepo->delete($id);
    }

    public function handleContactSubmission(array $data)
    {
        return $this->contactRepo->store($data);
    }
}
