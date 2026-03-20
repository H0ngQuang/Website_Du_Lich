@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Tích điểm</h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <!-- Stats -->
                <div class="row tile_count" style="margin-bottom:20px;">
                    @php
                        $tierLabels = ['bronze' => 'Đồng', 'silver' => 'Bạc', 'gold' => 'Vàng', 'platinum' => 'Kim cương'];
                        $tierIcons = ['bronze' => '🥉', 'silver' => '🥈', 'gold' => '🥇', 'platinum' => '💎'];
                    @endphp
                    @foreach($tierStats as $stat)
                    <div class="col-md-3 col-sm-6 tile_stats_count">
                        <span class="count_top">
                            {{ $tierIcons[$stat->loyalty_tier] ?? '' }}
                            {{ $tierLabels[$stat->loyalty_tier] ?? ucfirst($stat->loyalty_tier) }}
                        </span>
                        <div class="count" style="font-size:24px;">{{ $stat->count }}</div>
                        <span class="count_bottom">
                            Tổng: {{ number_format($stat->total_revenue ?? 0, 0, ',', '.') }} VND
                        </span>
                    </div>
                    @endforeach
                </div>

                <!-- Customer Table -->
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Top khách hàng</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>Tổng chi tiêu</th>
                                    <th>Booking</th>
                                    <th>Điểm</th>
                                    <th>Hạng</th>
                                    <th>Booking cuối</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $customer->username }}</strong></td>
                                    <td>{{ $customer->email }}</td>
                                    <td style="color:#dc2626;font-weight:600;">
                                        {{ number_format($customer->total_spent, 0, ',', '.') }} VND
                                    </td>
                                    <td>{{ $customer->total_bookings }}</td>
                                    <td>{{ number_format($customer->points) }}</td>
                                    <td>
                                        @php
                                            $tierColors = ['bronze' => '#d4a373', 'silver' => '#94a3b8', 'gold' => '#f59e0b', 'platinum' => '#6366f1'];
                                        @endphp
                                        <span style="color:{{ $tierColors[$customer->loyalty_tier] ?? '#333' }};font-weight:700;">
                                            {{ $tierIcons[$customer->loyalty_tier] ?? '' }}
                                            {{ $tierLabels[$customer->loyalty_tier] ?? ucfirst($customer->loyalty_tier) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($customer->last_booking_at)
                                            {{ date('d/m/Y', strtotime($customer->last_booking_at)) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>
@include('admin.blocks.footer')
