<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository
{
    public function getAll()
    {
        return Contact::latest()->paginate(8);
    }

    public function findById($id)
    {
        return Contact::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $contact = $this->findById($id);
        $contact->update($data);
        return $contact;
    }

    public function delete($id)
    {
        $contact = $this->findById($id);
        return $contact->delete();
    }

    public function store(array $data)
    {
        return Contact::create($data);
    }
}
