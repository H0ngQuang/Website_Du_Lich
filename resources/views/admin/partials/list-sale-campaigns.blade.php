@foreach ($campaigns as $campaign)
    <tr>
        <td>{{ $campaign->id }}</td>
        <td>{{ $campaign->name }}</td>
        <td>
            @if($campaign->type === 'flash_sale')
                <span class="label label-danger">Flash Sale</span>
            @elseif($campaign->type === 'seasonal')
                <span class="label label-warning">Mùa vụ</span>
            @else
                <span class="label label-info">Từng Tour</span>
            @endif
        </td>
        <td><strong>{{ $campaign->discount_percent }}%</strong></td>
        <td>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y H:i') }}</td>
        <td>{{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y H:i') }}</td>
        <td>
            @if($campaign->apply_to === 'all')
                <span class="label label-success">Tất cả</span>
            @else
                <span class="label label-primary">{{ $campaign->tours->count() }} tour</span>
            @endif
        </td>
        <td>
            @php
                $now = \Carbon\Carbon::now();
                $isRunning = $campaign->is_active && $now->between($campaign->start_date, $campaign->end_date);
                $isExpired = $now->gt($campaign->end_date);
            @endphp
            @if($isRunning)
                <span class="label label-success"><i class="fa fa-check"></i> Đang chạy</span>
            @elseif($isExpired)
                <span class="label label-default">Hết hạn</span>
            @elseif($campaign->is_active)
                <span class="label label-warning">Chờ bắt đầu</span>
            @else
                <span class="label label-default">Tắt</span>
            @endif
        </td>
        <td>
            {{-- Toggle Kích hoạt / Tắt - Luôn hiển thị để admin dễ quản lý --}}
            @if($campaign->is_active)
                <button type="button" class="btn btn-sm btn-warning toggle-campaign" title="Tắt chiến dịch"
                    data-id="{{ $campaign->id }}" data-action="deactivate">
                    <i class="fa fa-power-off"></i>
                </button>
            @else
                <button type="button" class="btn btn-sm btn-success toggle-campaign" title="Kích hoạt chiến dịch"
                    data-id="{{ $campaign->id }}" data-action="activate">
                    <i class="fa fa-power-off"></i>
                </button>
            @endif
            <button type="button" class="btn btn-sm btn-primary edit-campaign"
                data-id="{{ $campaign->id }}"
                data-name="{{ $campaign->name }}"
                data-type="{{ $campaign->type }}"
                data-discount="{{ $campaign->discount_percent }}"
                data-start="{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y H:i') }}"
                data-end="{{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y H:i') }}"
                data-active="{{ $campaign->is_active }}"
                data-applyto="{{ $campaign->apply_to }}"
                data-tours="{{ $campaign->tours->pluck('tourId')->toJson() }}">
                <i class="fa fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger delete-campaign"
                data-id="{{ $campaign->id }}">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
