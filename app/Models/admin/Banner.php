<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'tbl_banners';
    protected $primaryKey = 'bannerId';

    /**
     * Lấy tất cả banners (Admin)
     */
    public function getAllBanners()
    {
        return DB::table($this->table)->orderBy('order_index')->orderByDesc('created_at')->get();
    }

    /**
     * Lấy banners đang active (Client)
     */
    public function getActiveBanners()
    {
        return DB::table($this->table)
            ->where('is_active', 1)
            ->orderBy('order_index')
            ->get();
    }

    /**
     * Tạo banner mới
     */
    public function createBanner($data)
    {
        $data['created_at'] = now();
        $data['updated_at'] = now();
        return DB::table($this->table)->insertGetId($data);
    }

    /**
     * Cập nhật banner
     */
    public function updateBanner($bannerId, $data)
    {
        $data['updated_at'] = now();
        return DB::table($this->table)
            ->where('bannerId', $bannerId)
            ->update($data);
    }

    /**
     * Xóa banner
     */
    public function deleteBanner($bannerId)
    {
        return DB::table($this->table)
            ->where('bannerId', $bannerId)
            ->delete();
    }

    /**
     * Lấy banner theo ID
     */
    public function getBanner($bannerId)
    {
        return DB::table($this->table)
            ->where('bannerId', $bannerId)
            ->first();
    }
}
