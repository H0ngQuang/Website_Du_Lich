<?php

namespace App\Models\clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'tbl_promotions';
    protected $primaryKey = 'promotionId';

    /**
     * Lấy tất cả promotions
     */
    public function getAllPromotions()
    {
        return DB::table($this->table)->orderByDesc('created_at')->get();
    }

    /**
     * Lấy promotions đang active
     */
    public function getActivePromotions()
    {
        return DB::table($this->table)
            ->where('is_active', 1)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->where(function ($q) {
                $q->where('usage_limit', 0)
                    ->orWhereRaw('used_count < usage_limit');
            })
            ->get();
    }

    /**
     * Lấy promotions khả dụng cho tier cụ thể
     */
    public function getPromotionsForTier($tier)
    {
        $tierOrder = ['bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4];
        $tierLevel = $tierOrder[$tier] ?? 1;

        $promotions = $this->getActivePromotions();

        return $promotions->filter(function ($promo) use ($tierOrder, $tierLevel) {
            $promoTierLevel = $tierOrder[$promo->min_tier] ?? 1;
            return $tierLevel >= $promoTierLevel;
        });
    }

    /**
     * Validate và áp dụng mã giảm giá
     */
    public function validateCode($code, $tier = 'bronze')
    {
        $promotion = DB::table($this->table)
            ->where('code', $code)
            ->where('is_active', 1)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();

        if (!$promotion) {
            return ['valid' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'];
        }

        if ($promotion->usage_limit > 0 && $promotion->used_count >= $promotion->usage_limit) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        $tierOrder = ['bronze' => 1, 'silver' => 2, 'gold' => 3, 'platinum' => 4];
        if (($tierOrder[$tier] ?? 1) < ($tierOrder[$promotion->min_tier] ?? 1)) {
            return ['valid' => false, 'message' => 'Tier của bạn chưa đủ để sử dụng mã này. Yêu cầu tối thiểu: ' . ucfirst($promotion->min_tier)];
        }

        return ['valid' => true, 'promotion' => $promotion];
    }

    /**
     * Tăng used_count
     */
    public function incrementUsage($promotionId)
    {
        return DB::table($this->table)
            ->where('promotionId', $promotionId)
            ->increment('used_count');
    }

    /**
     * Tạo promotion mới
     */
    public function createPromotion($data)
    {
        $data['created_at'] = now();
        $data['updated_at'] = now();
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * Cập nhật promotion
     */
    public function updatePromotion($promotionId, $data)
    {
        $data['updated_at'] = now();
        return DB::table($this->table)
            ->where('promotionId', $promotionId)
            ->update($data);
    }

    /**
     * Xóa promotion
     */
    public function deletePromotion($promotionId)
    {
        return DB::table($this->table)
            ->where('promotionId', $promotionId)
            ->delete();
    }

    /**
     * Lấy promotion random cho tier (dùng khi gửi email ưu đãi)
     */
    public function getRandomPromotionForTier($tier)
    {
        $promotions = $this->getPromotionsForTier($tier);
        return $promotions->isNotEmpty() ? $promotions->random() : null;
    }
}
