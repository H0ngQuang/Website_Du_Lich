@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý <small>Booking</small></h3>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Booking</h2>

                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>

                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive">
                                            <p class="text-muted font-13 m-b-30">
                                                Chào mừng bạn đến với trang quản lý tour đã đặt. Tại đây, bạn có thể xác nhận,
                                                xem chi tiết, và quản lý tất cả các tour đã được đặt hiện có.
                                            </p>
                                            <table id="datatable-booking" class="table table-striped table-bordered">
                                                <colgroup>
                                                    <col style="width: 25% !important; min-width: 250px !important;">
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col style="min-width: 200px !important;">
                                                    <col>
                                                    <col style="width: 50px !important;">
                                                    <col style="width: 50px !important;">
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                    <col>
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th>Tên Tours</th>
                                                        <th>Tên KH</th>
                                                        <th>Email</th>
                                                        <th>Số điện thoại</th>
                                                        <th>Địa chỉ</th>
                                                        <th>Ngày đặt</th>
                                                        <th title="Người lớn">NL</th>
                                                        <th title="Trẻ em">TE</th>
                                                        <th>Tổng tiền</th>
                                                        <th>TT Booking</th>
                                                        <th>Thanh toán</th>
                                                        <th>Trạng thái</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-booking">
                                                    {{-- @include('admin.partials.list-booking') --}}
                                                    <tr>
                                                        <td>BIỂN ĐẢO 3N2Đ | PHÚ QUỐC (Khởi hành mỗi ngày)</td>
                                                        <td title="Nguyễn Văn A">Nguyễn Văn A</td>
                                                        <td title="nva752k4@gmail.com">nva752k4@gmail.com</td>
                                                        <td>0132456789</td>
                                                        <td title="KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội">KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội</td>
                                                        <td>31/12/2024</td>
                                                        <td>5</td>
                                                        <td>2</td>
                                                        <td>10.123.456</td>
                                                        <td>
                                                            <span class="badge badge-primary">Đã xác nhận</span>
                                                        </td>
                                                        <td>
                                                            <img src="{{ asset('admin/assets/images/icon/icon_paypal.png') }}"
                                                                class="icon_payment" alt="PayPal">
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">Đã thanh toán</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, 38px, 0px);">
                                                                    <a class="dropdown-item" href="#">Xem chi tiết</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>BIỂN ĐẢO 3N2Đ | PHÚ QUỐC (Khởi hành mỗi ngày)</td>
                                                        <td title="Phạm Hồng Quang">Phạm Hồng Quang</td>
                                                        <td title="hongquang02082004@gmail.com">hongquang02082004@gmail.com</td>
                                                        <td>0132456789</td>
                                                        <td title="KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội">KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội</td>
                                                        <td>31/12/2024</td>
                                                        <td>5</td>
                                                        <td>2</td>
                                                        <td>10.123.456</td>
                                                        <td>
                                                            <span class="badge badge-warning">Chưa xác nhận</span>
                                                        </td>
                                                        <td>
                                                            <img src="{{ asset('admin/assets/images/icon/icon_momo.png') }}"
                                                                class="icon_payment" alt="MoMo">
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-danger">Chưa thanh toán</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, 38px, 0px);">
                                                                    <a class="dropdown-item" href="#">Xem chi tiết</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>BIỂN ĐẢO 3N2Đ | PHÚ QUỐC (Khởi hành mỗi ngày)</td>
                                                        <td title="Đỗ Tuấn Nghĩa">Đỗ Tuấn Nghĩa</td>
                                                        <td title="dtn752k4@gmail.com">dtn752k4@gmail.com</td>
                                                        <td>0132456789</td>
                                                        <td title="KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội">KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội</td>
                                                        <td>31/12/2024</td>
                                                        <td>5</td>
                                                        <td>2</td>
                                                        <td>10.123.456</td>
                                                        <td>
                                                            <span class="badge badge-danger">Đã hủy</span>
                                                        </td>
                                                        <td>
                                                            <img src="{{ asset('admin/assets/images/icon/icon_office.png') }}"
                                                                class="icon_payment" alt="Office">
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-danger">Chưa thanh toán</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, 38px, 0px);">
                                                                    <a class="dropdown-item" href="#">Xem chi tiết</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>BIỂN ĐẢO 3N2Đ | PHÚ QUỐC (Khởi hành mỗi ngày)</td>
                                                        <td title="Nguyễn Văn C">Nguyễn Văn C</td>
                                                        <td title="nvC@gmail.com">nvC@gmail.com</td>
                                                        <td>0132456789</td>
                                                        <td title="KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội">KM10 Nguyễn Trãi, Thanh Xuân, Hà Nội</td>
                                                        <td>31/12/2024</td>
                                                        <td>5</td>
                                                        <td>2</td>
                                                        <td>10.123.456</td>
                                                        <td>
                                                            <span class="badge badge-success">Đã hoàn thành</span>
                                                        </td>
                                                        <td>
                                                            <img src="{{ asset('admin/assets/images/icon/icon_paypal.png') }}"
                                                                class="icon_payment" alt="PayPal">
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">Đã thanh toán</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="bottom-start"
                                                                    style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, 38px, 0px);">
                                                                    <a class="dropdown-item" href="#">Xem chi tiết</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<script>
// Disable scroll restoration
if (history.scrollRestoration) {
    history.scrollRestoration = 'manual';
}

// Force scroll to top immediately
window.addEventListener('load', function() {
    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
});

// Also scroll to top on ready
$(document).ready(function() {
    setTimeout(function() {
        window.scrollTo(0, 0);
        $('html, body').scrollTop(0);
    }, 50);
});
</script>

@include('admin.blocks.footer')
