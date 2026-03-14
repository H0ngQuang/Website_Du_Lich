<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Mã</th>
            <th>Mô tả</th>
            <th>Giảm giá</th>
            <th>Thời hạn</th>
            <th>Đã dùng</th>
            <th>Tier</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $index => $promo)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td><strong style="letter-spacing:2px;">{{ $promo->code }}</strong></td>
            <td>{{ $promo->description ?? '-' }}</td>
            <td>
                @if($promo->discount_percent > 0)
                    <span class="label label-success">{{ $promo->discount_percent }}%</span>
                @elseif($promo->discount_amount > 0)
                    <span class="label label-info">{{ number_format($promo->discount_amount, 0, ',', '.') }} VND</span>
                @endif
            </td>
            <td>{{ date('d/m/Y', strtotime($promo->valid_from)) }} - {{ date('d/m/Y', strtotime($promo->valid_until)) }}</td>
            <td>
                {{ $promo->used_count }}{{ $promo->usage_limit > 0 ? '/' . $promo->usage_limit : '/∞' }}
            </td>
            <td>
                @php
                    $tierColors = ['bronze' => '#d4a373', 'silver' => '#94a3b8', 'gold' => '#f59e0b', 'platinum' => '#6366f1'];
                @endphp
                <span style="color:{{ $tierColors[$promo->min_tier] ?? '#333' }};font-weight:600;">
                    {{ ucfirst($promo->min_tier) }}+
                </span>
            </td>
            <td>
                @if($promo->is_active && $promo->valid_until >= date('Y-m-d'))
                    <span class="label label-success">Active</span>
                @else
                    <span class="label label-danger">Inactive</span>
                @endif
            </td>
            <td>
                <button class="btn btn-danger btn-xs btn-delete-promo" data-id="{{ $promo->promotionId }}">
                    <i class="fa fa-trash"></i> Xóa
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
