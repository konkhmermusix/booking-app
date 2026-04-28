<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AboutRepository;
use App\Services\AboutService;
use App\Models\HotelHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $aboutService;
    protected $aboutRepository;

    public function __construct(AboutService $aboutService, AboutRepository $aboutRepository)
    {
        $this->aboutService = $aboutService;
        $this->aboutRepository = $aboutRepository;
    }

    /**
     * បង្ហាញបញ្ជីប្រវត្តិទាំងអស់
     */
    public function index()
    {
        $histories = $this->aboutRepository->getAllHistories();
        return view('admin.history.index', compact('histories'));
    }

    /**
     * បង្ហាញហ្វមបង្កើតថ្មី
     */
    public function create()
    {
        return view('admin.history.index'); // ប្រើ index ដើម្បីបង្ហាញទាំងបញ្ជី និងហ្វមបង្កើតថ្មី
    }

    /**
     * រក្សាទុកទិន្នន័យថ្មី
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'title_kh' => 'required',
            'description_kh' => 'required',
        ]);

        // ចំណាំ៖ ប្រើ $this->aboutService ព្រោះអ្នកបានចាក់បញ្ចូលក្នុង __construct
        HotelHistory::create($request->all());

        return redirect()->route('history.index')->with('success', 'បង្កើតប្រវត្តិថ្មីជោគជ័យ');
    }

    /**
     * បង្ហាញហ្វមកែប្រែ
     */
    public function edit($id)
    {
        $history = HotelHistory::findOrFail($id);
        return view('admin.history.edit', compact('history'));
    }

    /**
     * ធ្វើបច្ចុប្បន្នភាពទិន្នន័យ
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required',
            'title_kh' => 'required',
            'description_kh' => 'required',
        ]);

        $history = HotelHistory::findOrFail($id);
        $history->update($request->all());

        // return redirect()->route('admin.history.index')->with('success', 'កែប្រែជោគជ័យ');
    }

    /**
     * លុបទិន្នន័យ
     */
    public function destroy($id)
    {
        $history = HotelHistory::findOrFail($id);
        $history->delete();

        return back()->with('success', 'លុបទិន្នន័យជោគជ័យ');
    }
}
