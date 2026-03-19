<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Banner;
use Illuminate\Http\Request;

class BannerManagementController extends Controller
{
    private $banner;

    public function __construct()
    {
        $this->banner = new Banner();
    }

    public function index()
    {
        $title = 'Quản lý Banner';
        $banners = $this->banner->getAllBanners();

        return view('admin.banners', compact('title', 'banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'title' => 'required|string|max:255',
        ]);

        $image = $request->file('image');
        $filename = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('clients/assets/images/banners'), $filename);

        $data = [
            'title'       => $request->input('title'),
            'subtitle'    => $request->input('subtitle'),
            'image'       => $filename,
            'link_url'    => $request->input('link_url'),
            'order_index' => $request->input('order_index', 0),
            'is_active'   => $request->input('is_active', 1),
        ];

        $this->banner->createBanner($data);

        return response()->json([
            'success' => true,
            'message' => 'Thêm banner thành công!',
            'data'    => view('admin.partials.list-banners', [
                'banners' => $this->banner->getAllBanners()
            ])->render()
        ]);
    }

    public function update(Request $request)
    {
        $bannerId = $request->input('bannerId');
        $banner = $this->banner->getBanner($bannerId);

        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Banner không tồn tại!']);
        }

        $data = [
            'title'       => $request->input('title'),
            'subtitle'    => $request->input('subtitle'),
            'link_url'    => $request->input('link_url'),
            'order_index' => $request->input('order_index', 0),
            'is_active'   => $request->input('is_active', 1),
        ];

        // Nếu có upload ảnh mới
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            ]);

            // Xóa ảnh cũ
            $oldPath = public_path('clients/assets/images/banners/' . $banner->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $image = $request->file('image');
            $filename = 'banner_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('clients/assets/images/banners'), $filename);
            $data['image'] = $filename;
        }

        $this->banner->updateBanner($bannerId, $data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật banner thành công!',
            'data'    => view('admin.partials.list-banners', [
                'banners' => $this->banner->getAllBanners()
            ])->render()
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $bannerId = $request->input('bannerId');
        $banner = $this->banner->getBanner($bannerId);

        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Banner không tồn tại!']);
        }

        $newStatus = $banner->is_active ? 0 : 1;
        $this->banner->updateBanner($bannerId, ['is_active' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus ? 'Đã bật hiển thị banner!' : 'Đã tắt hiển thị banner!',
            'data'    => view('admin.partials.list-banners', [
                'banners' => $this->banner->getAllBanners()
            ])->render()
        ]);
    }

    public function destroy(Request $request)
    {
        $bannerId = $request->input('bannerId');
        $banner = $this->banner->getBanner($bannerId);

        if ($banner) {
            // Xóa file ảnh
            $imagePath = public_path('clients/assets/images/banners/' . $banner->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $this->banner->deleteBanner($bannerId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Xóa banner thành công!',
            'data'    => view('admin.partials.list-banners', [
                'banners' => $this->banner->getAllBanners()
            ])->render()
        ]);
    }
}
