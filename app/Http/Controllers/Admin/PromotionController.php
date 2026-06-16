<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Services\PromotionService;
use App\Repositories\PromotionRepository;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PromotionController extends Controller
{

    public function __construct(
        protected PromotionService $service,
        protected PromotionRepository $repository
    ) {}

    
    public function index(Request $request): mixed
    {
        $promotions = $this->repository->getAll(
            $request->search,
            $request->status,
            $request->get('per_page', 10)
        );

        $roomTypes = RoomType::all();

        if ($request->ajax()) {
            return view('admin.promotions.partials.promo_list', compact('promotions'))->render();
        }

        return view('admin.promotions.index', compact('promotions', 'roomTypes'));
    }

    public function store(PromotionRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->service->storePromotion($data);

            return back()->with('success', 'បង្កើតការបញ្ចុះតម្លៃបានជោគជ័យ!');
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()
            ]);
        }
    }

    public function update(PromotionRequest $request, int $id): RedirectResponse
    {
        $this->service->updatePromotion($id, $request->validated());
        return back()->with('success', 'ធ្វើបច្ចុប្បន្នភាពបានជោគជ័យ!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->deletePromotion($id);
        return back()->with('success', 'លុបការបញ្ចុះតម្លៃជោគជ័យ!');
    }
}
