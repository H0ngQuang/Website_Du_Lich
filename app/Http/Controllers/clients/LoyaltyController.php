<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\CustomerLoyalty;
use App\Models\clients\Promotion;
use App\Models\clients\TourView;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    private $loyalty;
    private $promotion;
    private $tourView;

    public function __construct()
    {
        parent::__construct();
        $this->loyalty = new CustomerLoyalty();
        $this->promotion = new Promotion();
        $this->tourView = new TourView();
    }

    public function index()
    {
        $title = 'Khách hàng thân thiết';
        $userId = $this->getUserId();

        // Lấy thông tin loyalty
        $loyaltyInfo = $this->loyalty->getOrCreate($userId);

        // Lấy thông tin tier tiếp theo
        $nextTierInfo = $this->loyalty->getNextTierInfo($loyaltyInfo->loyalty_tier, $loyaltyInfo->total_spent);

        // Lấy lịch sử booking
        $bookingHistory = $this->loyalty->getBookingHistory($userId);

        // Lấy promotions khả dụng
        $promotions = $this->promotion->getPromotionsForTier($loyaltyInfo->loyalty_tier);

        // Lấy tour đã xem gần đây
        $recentViews = $this->tourView->getRecentViews($userId, 5);

        return view('clients.loyalty-dashboard', compact(
            'title',
            'loyaltyInfo',
            'nextTierInfo',
            'bookingHistory',
            'promotions',
            'recentViews'
        ));
    }

    public function applyPromotion(Request $request)
    {
        $userId = $this->getUserId();
        $code = $request->input('code');

        $loyaltyInfo = $this->loyalty->getOrCreate($userId);
        $result = $this->promotion->validateCode($code, $loyaltyInfo->loyalty_tier);

        return response()->json($result);
    }
}
