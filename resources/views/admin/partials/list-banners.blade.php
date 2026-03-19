<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th style="width:120px;">Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Mô tả phụ</th>
            <th>Link URL</th>
            <th>Thứ tự</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banners as $index => $banner)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                <img src="{{ asset('clients/assets/images/banners/' . $banner->image) }}" alt="{{ $banner->title }}"
                    style="width:110px; height:65px; object-fit:cover; border-radius:4px;">
            </td>
            <td><strong>{{ $banner->title }}</strong></td>
            <td>{{ $banner->subtitle ?? '-' }}</td>
            <td>
                @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" target="_blank" class="text-primary" title="{{ $banner->link_url }}">
                        <i class="fa fa-external-link"></i> Xem
                    </a>
                @else
                    -
                @endif
            </td>
            <td>{{ $banner->order_index }}</td>
            <td>
                @if($banner->is_active)
                    <span class="label label-success">Hiển thị</span>
                @else
                    <span class="label label-danger">Ẩn</span>
                @endif
            </td>
            <td>
                <button class="btn btn-warning btn-xs btn-edit-banner"
                    data-id="{{ $banner->bannerId }}"
                    data-title="{{ $banner->title }}"
                    data-subtitle="{{ $banner->subtitle }}"
                    data-link="{{ $banner->link_url }}"
                    data-order="{{ $banner->order_index }}"
                    data-active="{{ $banner->is_active }}">
                    <i class="fa fa-edit"></i> Sửa
                </button>
                <button class="btn btn-{{ $banner->is_active ? 'default' : 'success' }} btn-xs btn-toggle-banner" data-id="{{ $banner->bannerId }}">
                    <i class="fa fa-{{ $banner->is_active ? 'eye-slash' : 'eye' }}"></i>
                    {{ $banner->is_active ? 'Ẩn' : 'Hiện' }}
                </button>
                <button class="btn btn-danger btn-xs btn-delete-banner" data-id="{{ $banner->bannerId }}">
                    <i class="fa fa-trash"></i> Xóa
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
