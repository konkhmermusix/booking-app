<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AboutRepository;
use App\Services\AboutService;
use App\Http\Requests\AboutRequest;
use Illuminate\Http\Request;
use App\Models\AboutContent;


class AboutController extends Controller
{
    protected $aboutService;

    public function __construct(AboutService $aboutService)
    {
        $this->aboutService = $aboutService;
    }

    public function index()
    {
        $contents = $this->aboutService->listAll();
        return view('admin.abouts.index', compact('contents'));
    }

    public function store(Request $request)
    {
        $data = $this->aboutService->storeData($request->all());
        return response()->json(['status' => 'success', 'message' => 'រក្សាទុកជោគជ័យ', 'data' => $data]);
    }

    public function edit($id)
    {
        $content = AboutContent::findOrFail($id);
        return response()->json($content);
    }
    
    public function update(Request $request, $id)
    {
        $this->aboutService->updateData($id, $request->all());
        return response()->json(['status' => 'success', 'message' => 'កែប្រែជោគជ័យ']);
    }

    public function destroy($id)
    {
        $this->aboutService->deleteData($id);
        return response()->json(['status' => 'success', 'message' => 'លុបជោគជ័យ']);
    }
}
