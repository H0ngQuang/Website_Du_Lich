@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý khuyến mãi</h3>
                    </div>
                    <div class="title_right">
                        <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addPromoModal">
                            <i class="fa fa-plus"></i> Thêm mã khuyến mãi
                        </button>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="x_panel">
                    <div class="x_content" id="promotions-list">
                        @include('admin.partials.list-promotions')
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<!-- Modal Thêm Promotion -->
<div class="modal fade" id="addPromoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm mã khuyến mãi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addPromoForm">
                    @csrf
                    <div class="form-group">
                        <label>Mã giảm giá</label>
                        <input type="text" name="code" class="form-control" placeholder="VD: TRAVELA2026" required style="text-transform:uppercase;">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <input type="text" name="description" class="form-control" placeholder="Mô tả ngắn">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giảm %</label>
                                <input type="number" name="discount_percent" class="form-control" value="0" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giảm VND</label>
                                <input type="number" name="discount_amount" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ngày bắt đầu</label>
                                <input type="date" name="valid_from" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ngày kết thúc</label>
                                <input type="date" name="valid_until" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Giới hạn sử dụng (0 = không giới hạn)</label>
                                <input type="number" name="usage_limit" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tier tối thiểu</label>
                                <select name="min_tier" class="form-control">
                                    <option value="bronze">Bronze</option>
                                    <option value="silver">Silver</option>
                                    <option value="gold">Gold</option>
                                    <option value="platinum">Platinum</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="btnAddPromo">Tạo mã</button>
            </div>
        </div>
    </div>
</div>

@include('admin.blocks.footer')

<script>
$(document).ready(function() {
    // Add promotion
    $('#btnAddPromo').click(function() {
        var formData = $('#addPromoForm').serialize();
        $.ajax({
            url: '{{ route("admin.add-promotion") }}',
            type: 'POST',
            data: formData,
            success: function(res) {
                if (res.success) {
                    $('#promotions-list').html(res.data);
                    $('#addPromoModal').modal('hide');
                    $('#addPromoForm')[0].reset();
                    alert(res.message);
                }
            },
            error: function(err) {
                alert('Lỗi: ' + (err.responseJSON?.message || 'Không thể tạo mã'));
            }
        });
    });

    // Delete promotion
    $(document).on('click', '.btn-delete-promo', function() {
        if (!confirm('Bạn có chắc muốn xóa mã này?')) return;
        var promoId = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.delete-promotion") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', promotionId: promoId },
            success: function(res) {
                if (res.success) {
                    $('#promotions-list').html(res.data);
                    alert(res.message);
                }
            }
        });
    });
});
</script>
