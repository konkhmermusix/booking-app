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

    public function listAllMessages()
    {
        return $this->contactRepo->getAll();
    }

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
