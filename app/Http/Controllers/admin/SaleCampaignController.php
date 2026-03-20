<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\SaleCampaign;
use App\Models\admin\ToursModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleCampaignController extends Controller
{
    private $campaign;
    private $tours;

    public function __construct()
    {
        $this->campaign = new SaleCampaign();
        $this->tours = new ToursModel();
    }

    /**
     * Parse date string flexibly (d/m/Y H:i or d/m/Y)
     */
    private function parseDate($dateStr)
    {
        try {
            return Carbon::createFromFormat('d/m/Y H:i', $dateStr);
        } catch (\Exception $e) {
            return Carbon::createFromFormat('d/m/Y', $dateStr)->startOfDay();
        }
    }

    public function index()
    {
        $title = 'Chiến dịch khuyến mại';
        $campaigns = $this->campaign->getAllCampaigns();
        $allTours = $this->tours->getAllTours();

        return view('admin.sale-campaigns', compact('title', 'campaigns', 'allTours'));
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'discount_percent' => $request->input('discount_percent'),
            'start_date' => $this->parseDate($request->input('start_date')),
            'end_date' => $this->parseDate($request->input('end_date')),
            'is_active' => $request->has('is_active') ? 1 : 0,
            'apply_to' => $request->input('apply_to'),
        ];

        $campaignId = $this->campaign->createCampaign($data);

        // Nếu apply_to = selected, lưu danh sách tour
        if ($data['apply_to'] === 'selected' && $request->has('tour_ids')) {
            $this->campaign->syncTours($campaignId, $request->input('tour_ids'));
        }

        $campaigns = $this->campaign->getAllCampaigns();

        return response()->json([
            'success' => true,
            'message' => 'Tạo chiến dịch thành công!',
            'data' => view('admin.partials.list-sale-campaigns', compact('campaigns'))->render()
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');

        $data = [
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'discount_percent' => $request->input('discount_percent'),
            'start_date' => $this->parseDate($request->input('start_date')),
            'end_date' => $this->parseDate($request->input('end_date')),
            'is_active' => $request->has('is_active') ? 1 : 0,
            'apply_to' => $request->input('apply_to'),
        ];

        $this->campaign->updateCampaign($id, $data);

        // Sync tour list
        if ($data['apply_to'] === 'selected' && $request->has('tour_ids')) {
            $this->campaign->syncTours($id, $request->input('tour_ids'));
        } else {
            $this->campaign->syncTours($id, []);
        }

        $campaigns = $this->campaign->getAllCampaigns();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chiến dịch thành công!',
            'data' => view('admin.partials.list-sale-campaigns', compact('campaigns'))->render()
        ]);
    }

    public function toggle(Request $request)
    {
        $id = $request->input('id');
        $action = $request->input('action'); // 'activate' or 'deactivate'
        $isActive = ($action === 'activate') ? 1 : 0;

        $this->campaign->updateCampaign($id, ['is_active' => $isActive]);

        $campaigns = $this->campaign->getAllCampaigns();

        return response()->json([
            'success' => true,
            'message' => ($isActive ? 'Kích hoạt' : 'Tắt') . ' chiến dịch thành công!',
            'data' => view('admin.partials.list-sale-campaigns', compact('campaigns'))->render()
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $this->campaign->deleteCampaign($id);

        $campaigns = $this->campaign->getAllCampaigns();

        return response()->json([
            'success' => true,
            'message' => 'Xóa chiến dịch thành công!',
            'data' => view('admin.partials.list-sale-campaigns', compact('campaigns'))->render()
        ]);
    }
}
