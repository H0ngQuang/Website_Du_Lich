<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleCampaign extends Model
{
    use HasFactory;

    protected $table = 'tbl_sale_campaigns';

    /**
     * Lấy tất cả campaigns kèm danh sách tour đã chọn
     */
    public function getAllCampaigns()
    {
        $campaigns = DB::table($this->table)
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($campaigns as $campaign) {
            $campaign->tours = DB::table('tbl_sale_campaign_tours')
                ->join('tbl_tours', 'tbl_sale_campaign_tours.tour_id', '=', 'tbl_tours.tourId')
                ->where('campaign_id', $campaign->id)
                ->select('tbl_tours.tourId', 'tbl_tours.title')
                ->get();
        }

        return $campaigns;
    }

    /**
     * Lấy campaigns đang active và trong thời hạn
     */
    public function getActiveCampaigns()
    {
        $now = Carbon::now();

        return DB::table($this->table)
            ->where('is_active', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();
    }

    /**
     * Lấy % giảm giá cao nhất cho 1 tour cụ thể
     */
    public function getDiscountForTour($tourId)
    {
        $campaign = $this->getBestCampaignForTour($tourId);
        return $campaign ? $campaign->discount_percent : 0;
    }

    /**
     * Lấy campaign tốt nhất đang áp dụng cho tour
     */
    public function getBestCampaignForTour($tourId)
    {
        $now = Carbon::now();

        // Lấy campaign áp dụng cho tất cả tour
        $globalCampaign = DB::table($this->table)
            ->where('is_active', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->where('apply_to', 'all')
            ->orderBy('discount_percent', 'desc')
            ->first();

        // Lấy campaign áp dụng cho tour cụ thể
        $specificCampaign = DB::table($this->table)
            ->join('tbl_sale_campaign_tours', 'tbl_sale_campaigns.id', '=', 'tbl_sale_campaign_tours.campaign_id')
            ->where('tbl_sale_campaign_tours.tour_id', $tourId)
            ->where('tbl_sale_campaigns.is_active', 1)
            ->where('tbl_sale_campaigns.start_date', '<=', $now)
            ->where('tbl_sale_campaigns.end_date', '>=', $now)
            ->where('tbl_sale_campaigns.apply_to', 'selected')
            ->orderBy('tbl_sale_campaigns.discount_percent', 'desc')
            ->select('tbl_sale_campaigns.*')
            ->first();

        if (!$globalCampaign && !$specificCampaign) {
            return null;
        }

        if (!$globalCampaign) return $specificCampaign;
        if (!$specificCampaign) return $globalCampaign;

        return ($specificCampaign->discount_percent >= $globalCampaign->discount_percent) 
            ? $specificCampaign 
            : $globalCampaign;
    }

    /**
     * Tạo campaign mới
     */
    public function createCampaign($data)
    {
        return DB::table($this->table)->insertGetId([
            'name' => $data['name'],
            'type' => $data['type'],
            'discount_percent' => $data['discount_percent'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => $data['is_active'] ?? 1,
            'apply_to' => $data['apply_to'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Cập nhật campaign
     */
    public function updateCampaign($id, $data)
    {
        $data['updated_at'] = Carbon::now();
        return DB::table($this->table)->where('id', $id)->update($data);
    }

    /**
     * Xóa campaign
     */
    public function deleteCampaign($id)
    {
        DB::table('tbl_sale_campaign_tours')->where('campaign_id', $id)->delete();
        return DB::table($this->table)->where('id', $id)->delete();
    }

    /**
     * Gán tours cho campaign (pivot table)
     */
    public function syncTours($campaignId, $tourIds)
    {
        DB::table('tbl_sale_campaign_tours')->where('campaign_id', $campaignId)->delete();

        if (!empty($tourIds)) {
            $inserts = [];
            foreach ($tourIds as $tourId) {
                $inserts[] = [
                    'campaign_id' => $campaignId,
                    'tour_id' => $tourId,
                ];
            }
            DB::table('tbl_sale_campaign_tours')->insert($inserts);
        }
    }
}
