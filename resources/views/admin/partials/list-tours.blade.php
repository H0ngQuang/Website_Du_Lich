@foreach ($tours as $tour)
    <tr>
        {{-- Tên tour - truncate with tooltip --}}
        <td>
            <span class="tour-name-cell" data-tooltip="{{ $tour->title }}" title="{{ $tour->title }}">
                {{ $tour->title }}
            </span>
        </td>

        {{-- Điểm đến - 2 line clamp --}}
        <td>
            <div class="tour-destination-cell" title="{{ $tour->destination }}">
                {{ $tour->destination }}
            </div>
        </td>

        {{-- Thời gian --}}
        <td>
            <span class="tour-time-cell">
                <i class="fa fa-clock-o"></i> {{ $tour->time }}
            </span>
        </td>

        {{-- Giá tour (NL + TE gộp) --}}
        <td>
            <div class="tour-price-cell">
                <span class="price-adult">
                    <span class="price-label">NL</span> {{ number_format($tour->priceAdult, 0, ',', '.') }}đ
                </span>
                <span class="price-child">
                    <span class="price-label">TE</span> {{ number_format($tour->priceChild, 0, ',', '.') }}đ
                </span>
            </div>
        </td>

        {{-- Số chỗ --}}
        <td>
            <span class="tour-quantity-cell">{{ $tour->quantity }}</span>
        </td>

        {{-- Lịch trình (ngày bắt đầu → kết thúc) --}}
        <td>
            <div class="tour-date-range">
                <div class="date-start">
                    <span class="date-icon"><i class="fa fa-play-circle"></i></span>
                    {{ date('d/m/Y', strtotime($tour->startDate)) }}
                </div>
                <div class="date-end">
                    <span class="date-icon"><i class="fa fa-stop-circle"></i></span>
                    {{ date('d/m/Y', strtotime($tour->endDate)) }}
                </div>
            </div>
        </td>

        {{-- Trạng thái (Badge) --}}
        <td>
            @if($tour->availability > 5)
                <span class="tour-status-badge status-active">
                    <span class="status-dot"></span> Còn chỗ
                </span>
            @elseif($tour->availability > 0)
                <span class="tour-status-badge status-limited">
                    <span class="status-dot"></span> Sắp hết
                </span>
            @else
                <span class="tour-status-badge status-inactive">
                    <span class="status-dot"></span> Hết chỗ
                </span>
            @endif
        </td>

        {{-- Thao tác (Xem mô tả + Sửa + Xóa) --}}
        <td>
            <div class="tour-actions">
                {{-- Nút xem mô tả --}}
                <button type="button" class="btn-action btn-action-view view-description"
                    title="Xem mô tả"
                    data-toggle="modal" data-target="#tour-description-modal"
                    data-tour-title="{{ $tour->title }}"
                    data-tour-description="{{ $tour->description }}">
                    <i class="fa fa-eye"></i>
                </button>

                {{-- Nút sửa --}}
                <button type="button" class="btn-action btn-action-edit edit-tour"
                    title="Chỉnh sửa"
                    data-toggle="modal" data-target="#edit-tour-modal"
                    data-tourId="{{ $tour->tourId }}"
                    data-urledit="{{ route('admin.tour-edit') }}">
                    <i class="fa fa-pencil"></i>
                </button>

                {{-- Nút xóa --}}
                <a href="{{ route('admin.delete-tour') }}" data-tourId="{{ $tour->tourId }}"
                    class="btn-action btn-action-delete delete-tour"
                    title="Xóa tour">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
