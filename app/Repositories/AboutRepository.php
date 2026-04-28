<?php

namespace App\Repositories;

use App\Models\AboutContent;

class AboutRepository
{
    public function getAll() {
        return AboutContent::latest()->get();
    }

    public function find($id) {
        return AboutContent::findOrFail($id);
    }

    public function create(array $data) {
        return AboutContent::create($data);
    }

    public function update($id, array $data) {
        $content = $this->find($id);
        $content->update($data);
        return $content;
    }

    public function delete($id) {
        return AboutContent::destroy($id);
    }
}