<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\clients\CustomerLoyalty;
use App\Models\clients\Promotion;
use Illuminate\Http\Request;

class PromotionManagementController extends Controller
{
    private $promotion;
    private $loyalty;

    public function __construct()
    {
        $this->promotion = new Promotion();
        $this->loyalty = new CustomerLoyalty();
    }

    public function index()
    {
        $title = 'Quản lý khuyến mãi';
        $promotions = $this->promotion->getAllPromotions();

        return view('admin.promotions', compact('title', 'promotions'));
    }

    public function store(Request $request)
    {
        $data = [
            'code' => strtoupper($request->input('code')),
            'description' => $request->input('description'),
            'discount_percent' => $request->input('discount_percent', 0),
            'discount_amount' => $request->input('discount_amount', 0),
            'valid_from' => $request->input('valid_from'),
            'valid_until' => $request->input('valid_until'),
            'usage_limit' => $request->input('usage_limit', 0),
            'min_tier' => $request->input('min_tier', 'bronze'),
            'is_active' => $request->input('is_active', 1),
        ];

        $this->promotion->createPromotion($data);

        return response()->json([
            'success' => true,
            'message' => 'Tạo mã khuyến mãi thành công!',
            'data' => view('admin.partials.list-promotions', [
                'promotions' => $this->promotion->getAllPromotions()
            ])->render()
        ]);
    }

    public function update(Request $request)
    {
        $promotionId = $request->input('promotionId');
        $data = [
            'code' => strtoupper($request->input('code')),
            'description' => $request->input('description'),
            'discount_percent' => $request->input('discount_percent', 0),
            'discount_amount' => $request->input('discount_amount', 0),
            'valid_from' => $request->input('valid_from'),
            'valid_until' => $request->input('valid_until'),
            'usage_limit' => $request->input('usage_limit', 0),
            'min_tier' => $request->input('min_tier', 'bronze'),
            'is_active' => $request->input('is_active', 1),
        ];

        $this->promotion->updatePromotion($promotionId, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mã khuyến mãi thành công!',
            'data' => view('admin.partials.list-promotions', [
                'promotions' => $this->promotion->getAllPromotions()
            ])->render()
        ]);
    }

    public function destroy(Request $request)
    {
        $promotionId = $request->input('promotionId');
        $this->promotion->deletePromotion($promotionId);

        return response()->json([
            'success' => true,
            'message' => 'Xóa mã khuyến mãi thành công!',
            'data' => view('admin.partials.list-promotions', [
                'promotions' => $this->promotion->getAllPromotions()
            ])->render()
        ]);
    }

    public function customerLoyalty()
    {
        $title = 'Khách hàng thân thiết';
        $topCustomers = $this->loyalty->getTopCustomers(50);
        $tierStats = $this->loyalty->getTierStats();

        return view('admin.customer-loyalty', compact('title', 'topCustomers', 'tierStats'));
    }
}
