<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutHistory;
use Illuminate\Http\Request;

class AboutHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = AboutHistory::query();

        // ស្វែងរក (Search)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title_kh', 'like', '%' . $request->search . '%')
                    ->orWhere('year', 'like', '%' . $request->search . '%');
            });
        }

        // ច្រោះតាមស្ថានភាព (Filter Status)
        // ត្រូវប្រើ $request ជំនួសឱ្យ $params ដែលមិនមានប្រកាស
        if ($request->filled('status') || $request->status === '0') {
            $query->where('status', $request->status);
        }

        $histories = $query->orderBy('sort_order', 'asc')->paginate(10);

        if ($request->ajax()) {
            // ប្រាកដថា File partials/history-list.blade.php មានពិតមែន
            return view('admin.abouts.partials.history-list', compact('histories'))->render();
        }

        return view('admin.abouts.index', compact('histories'));
    }

    // បង្ហាញទិន្នន័យសម្រាប់ Edit (JSON)
    public function edit($id)
    {
        $history = AboutHistory::findOrFail($id);
        return response()->json($history);
    }

    // មុខងារ Store និង Update (រួមគ្នា)
    public function store(Request $request)
    {
        $rules = [
            'year'           => 'required',
            'title_kh'       => 'required|max:255',
            'description_kh' => 'required',
            'sort_order'     => 'nullable|integer',
            'status'         => 'required|boolean',
        ];

        $data = $request->validate($rules);

        // ប្រើ updateOrCreate ដើម្បីកាត់បន្ថយការសរសេរកូដច្រើនដង
        $history = AboutHistory::updateOrCreate(
            ['id' => $request->id], // បើមាន ID វានឹង Update បើអត់ទេវានឹង Create ថ្មី
            $data
        );

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'កែសម្រួលជោគជ័យ!' : 'រក្សាទុកជោគជ័យ!',
            'data'    => $history
        ]);
    }

    public function destroy($id)
    {
        $history = AboutHistory::find($id);
        if ($history) {
            $history->delete();
            return response()->json(['success' => true, 'message' => 'លុបទិន្នន័យរួចរាល់!']);
        }
        return response()->json(['success' => false, 'message' => 'រកមិនឃើញទិន្នន័យ!'], 404);
    }
}
