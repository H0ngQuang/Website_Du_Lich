@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>{{ $title }}</h3>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Danh sách chiến dịch</h2>
                                <div class="nav navbar-right">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#campaign-modal" id="btn-add-campaign">
                                        <i class="fa fa-plus"></i> Thêm chiến dịch
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action">
                                        <thead>
                                            <tr class="headings">
                                                <th>ID</th>
                                                <th>Tên chiến dịch</th>
                                                <th>Loại</th>
                                                <th>Giảm giá</th>
                                                <th>Bắt đầu</th>
                                                <th>Kết thúc</th>
                                                <th>Áp dụng</th>
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-campaigns">
                                            @include('admin.partials.list-sale-campaigns')
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Thêm/Sửa Campaign --}}
        <div class="modal fade" id="campaign-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        <h4 class="modal-title" id="campaign-modal-title">Thêm chiến dịch khuyến mại</h4>
                    </div>
                    <div class="modal-body">
                        <form id="campaign-form">
                            @csrf
                            <input type="hidden" name="id" id="campaign-id">

                            <div class="form-group">
                                <label>Tên chiến dịch <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="campaign-name" placeholder="VD: Flash Sale Tháng 3, Sale Hè 2026..." required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại chiến dịch</label>
                                        <select class="form-control" name="type" id="campaign-type">
                                            <option value="flash_sale">⚡ Flash Sale</option>
                                            <option value="seasonal">🌞 Sale mùa vụ</option>
                                            <option value="single_tour">🎯 Sale từng tour</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phần trăm giảm giá (%)</label>
                                        <input type="number" class="form-control" name="discount_percent" id="campaign-discount" min="1" max="100" value="10" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Thời gian bắt đầu</label>
                                        <input type="text" class="form-control datetimepicker-campaign" name="start_date" id="campaign-start" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Thời gian kết thúc</label>
                                        <input type="text" class="form-control datetimepicker-campaign" name="end_date" id="campaign-end" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Áp dụng cho</label>
                                        <select class="form-control" name="apply_to" id="campaign-apply-to">
                                            <option value="all">Tất cả tour</option>
                                            <option value="selected">Chọn tour cụ thể</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="is_active" id="campaign-active" checked> Kích hoạt ngay
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Chọn tour (ẩn khi apply_to = all) --}}
                            <div class="form-group" id="tour-select-wrap" style="display:none;">
                                <label>Chọn tour áp dụng</label>
                                <select class="form-control" name="tour_ids[]" id="campaign-tours" multiple size="8">
                                    @foreach($allTours as $tour)
                                        <option value="{{ $tour->tourId }}">{{ $tour->title }} (ID: {{ $tour->tourId }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Giữ Ctrl để chọn nhiều tour</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-success" id="btn-save-campaign">
                            <i class="fa fa-save"></i> Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
    </div>
</div>

@include('admin.blocks.footer')

<script>
$(document).ready(function() {
    // Datetimepicker cho campaign
    $(".datetimepicker-campaign").datetimepicker({
        format: "d/m/Y H:i",
        step: 30
    });

    // Toggle tour select
    $("#campaign-apply-to").change(function() {
        if ($(this).val() === "selected") {
            $("#tour-select-wrap").slideDown();
        } else {
            $("#tour-select-wrap").slideUp();
        }
    });

    // Auto-set type khi chọn apply_to
    $("#campaign-apply-to").change(function() {
        if ($(this).val() === "all") {
            var currentType = $("#campaign-type").val();
            if (currentType === "single_tour") {
                $("#campaign-type").val("flash_sale");
            }
        } else {
            $("#campaign-type").val("single_tour");
        }
    });

    // Reset form khi mở modal thêm mới
    $("#btn-add-campaign").click(function() {
        $("#campaign-modal-title").text("Thêm chiến dịch khuyến mại");
        $("#campaign-id").val("");
        $("#campaign-form")[0].reset();
        $("#campaign-active").prop("checked", true);
        $("#tour-select-wrap").slideUp();
    });

    // Sửa campaign
    $(document).on("click", ".edit-campaign", function() {
        var btn = $(this);
        $("#campaign-modal-title").text("Sửa chiến dịch khuyến mại");
        $("#campaign-id").val(btn.data("id"));
        $("#campaign-name").val(btn.data("name"));
        $("#campaign-type").val(btn.data("type"));
        $("#campaign-discount").val(btn.data("discount"));
        $("#campaign-start").val(btn.data("start"));
        $("#campaign-end").val(btn.data("end"));
        $("#campaign-active").prop("checked", btn.data("active") == 1);
        $("#campaign-apply-to").val(btn.data("applyto"));

        if (btn.data("applyto") === "selected") {
            $("#tour-select-wrap").show();
            var tourIds = btn.data("tours");
            $("#campaign-tours").val(tourIds);
        } else {
            $("#tour-select-wrap").hide();
        }

        $("#campaign-modal").modal("show");
    });

    // Lưu campaign (thêm mới hoặc cập nhật)
    $("#btn-save-campaign").click(function() {
        var id = $("#campaign-id").val();
        var url = id ? "{{ route('admin.sale-campaigns.update') }}" : "{{ route('admin.sale-campaigns.store') }}";

        var formData = $("#campaign-form").serialize();

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    $("#tbody-campaigns").html(response.data);
                    $("#campaign-modal").modal("hide");
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message || "Có lỗi xảy ra.");
                }
            },
            error: function(xhr) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
                console.error(xhr.responseText);
            }
        });
    });

    // Xóa campaign
    $(document).on("click", ".delete-campaign", function() {
        if (!confirm("Bạn có chắc chắn muốn xóa chiến dịch này?")) return;

        var id = $(this).data("id");

        $.ajax({
            url: "{{ route('admin.sale-campaigns.destroy') }}",
            method: "POST",
            data: {
                id: id,
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                if (response.success) {
                    $("#tbody-campaigns").html(response.data);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            }
        });
    });

    // Toggle campaign (Kích hoạt/Tắt)
    $(document).on("click", ".toggle-campaign", function() {
        var id = $(this).data("id");
        var action = $(this).data("action");
        var confirmMsg = (action === 'activate') ? "Bạn có muốn kích hoạt chiến dịch này?" : "Bạn có muốn tắt chiến dịch này?";
        
        if (!confirm(confirmMsg)) return;

        $.ajax({
            url: "{{ route('admin.sale-campaigns.toggle') }}",
            method: "POST",
            data: {
                id: id,
                action: action,
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                if (response.success) {
                    $("#tbody-campaigns").html(response.data);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            }
        });
    });
});
</script>

