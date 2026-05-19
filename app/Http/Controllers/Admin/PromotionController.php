<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest; // ផ្ទៀងផ្ទាត់ Namespace របស់ Request ឡើងវិញ
use App\Services\PromotionService;
use App\Repositories\PromotionRepository;
use App\Models\RoomType;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $service;
    protected $repository; // បន្ថែមវាជា Property ឱ្យច្បាស់លាស់

    public function __construct(PromotionService $service, PromotionRepository $repository)
    {
        $this->service = $service;
        $this->repository = $repository; // កែពី $this->repo មកជា $this->repository ឱ្យស៊ីគ្នា
    }

    public function index(Request $request)
    {
        // កែពី $this->repo មកជា $this->repository
        $promotions = $this->repository->getAll(
            $request->search,
            $request->status,
            $request->get('per_page', 8)
        );

        // ទាញយក RoomType សម្រាប់ដាក់ក្នុង Select Box ពេល Add/Edit
        $roomTypes = RoomType::all();

        if ($request->ajax()) {
            return view('admin.promotions.partials.promo-list', compact('promotions'))->render();
        }

        return view('admin.promotions.index', compact('promotions', 'roomTypes'));
    }

    public function store(PromotionRequest $request)
    {
        try {
            // ១. ប្រើ $request->validated() ដើម្បីសុវត្ថិភាពទិន្នន័យ
            $data = $request->validated();

            // ២. បញ្ជូនទិន្នន័យទៅ Service ដើម្បីបង្កើត Promotion
            $this->service->storePromotion($data);

            return back()->with([
                'status' => 'success',
                'message' => 'បង្កើតការបញ្ចុះតម្លៃជោគជ័យ'
            ], 200);
        } catch (\Exception $e) {
            // ៣. បន្ថែម Catch ដើម្បីដឹងថា Error អ្វីឱ្យពិតប្រាកដ (ឧទាហរណ៍៖ SQL Error)
            return back()->with([
                'status' => 'error',
                'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(PromotionRequest $request, $id)
    {
        $this->service->updatePromotion($id, $request->validated());
        return back()->with(['status' => 'success', 'message' => 'កែប្រែជោគជ័យ']);
    }

    public function destroy($id)
    {
        $this->service->deletePromotion($id);
        return back()->with(['status' => 'success', 'message' => 'លុបជោគជ័យ']);
    }
}
