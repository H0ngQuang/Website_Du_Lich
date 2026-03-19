@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý Banner</h3>
                    </div>
                    <div class="title_right">
                        <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addBannerModal">
                            <i class="fa fa-plus"></i> Thêm Banner mới
                        </button>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="x_panel">
                    <div class="x_content" id="banners-list">
                        @include('admin.partials.list-banners')
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<!-- Modal Thêm Banner -->
<div class="modal fade" id="addBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm Banner mới</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addBannerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Hình ảnh Banner <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Kích thước khuyến nghị: 1920x800px. Tối đa 10MB.</small>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="VD: Khám phá Đà Nẵng" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả phụ</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="VD: Giảm 20% cho mùa hè">
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Link URL (khi click vào banner)</label>
                                <input type="url" name="link_url" class="form-control" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Thứ tự hiển thị</label>
                                <input type="number" name="order_index" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="btnAddBanner">Thêm Banner</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Banner -->
<div class="modal fade" id="editBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉnh sửa Banner</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editBannerForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="bannerId" id="editBannerId">
                    <div class="form-group">
                        <label>Hình ảnh Banner (để trống nếu không đổi)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Kích thước khuyến nghị: 1920x800px. Tối đa 10MB.</small>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả phụ</label>
                        <input type="text" name="subtitle" id="editSubtitle" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Link URL</label>
                                <input type="url" name="link_url" id="editLinkUrl" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Thứ tự</label>
                                <input type="number" name="order_index" id="editOrderIndex" class="form-control" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="is_active" id="editIsActive" class="form-control">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnUpdateBanner">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

@include('admin.blocks.footer')

<script>
$(document).ready(function() {
    // Add banner
    $('#btnAddBanner').click(function() {
        var formData = new FormData($('#addBannerForm')[0]);
        $.ajax({
            url: '{{ route("admin.add-banner") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    $('#banners-list').html(res.data);
                    $('#addBannerModal').modal('hide');
                    $('#addBannerForm')[0].reset();
                    alert(res.message);
                }
            },
            error: function(err) {
                var msg = 'Không thể thêm banner.';
                if (err.responseJSON && err.responseJSON.errors) {
                    var errors = err.responseJSON.errors;
                    msg = Object.values(errors).flat().join('\n');
                } else if (err.responseJSON && err.responseJSON.message) {
                    msg = err.responseJSON.message;
                }
                alert('Lỗi: ' + msg);
            }
        });
    });

    // Open edit modal
    $(document).on('click', '.btn-edit-banner', function() {
        var btn = $(this);
        $('#editBannerId').val(btn.data('id'));
        $('#editTitle').val(btn.data('title'));
        $('#editSubtitle').val(btn.data('subtitle'));
        $('#editLinkUrl').val(btn.data('link'));
        $('#editOrderIndex').val(btn.data('order'));
        $('#editIsActive').val(btn.data('active'));
        $('#editBannerModal').modal('show');
    });

    // Update banner
    $('#btnUpdateBanner').click(function() {
        var formData = new FormData($('#editBannerForm')[0]);
        $.ajax({
            url: '{{ route("admin.update-banner") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    $('#banners-list').html(res.data);
                    $('#editBannerModal').modal('hide');
                    alert(res.message);
                }
            },
            error: function(err) {
                alert('Lỗi: ' + (err.responseJSON?.message || 'Không thể cập nhật'));
            }
        });
    });

    // Toggle status
    $(document).on('click', '.btn-toggle-banner', function() {
        var bannerId = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.toggle-banner") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', bannerId: bannerId },
            success: function(res) {
                if (res.success) {
                    $('#banners-list').html(res.data);
                    alert(res.message);
                }
            }
        });
    });

    // Delete banner
    $(document).on('click', '.btn-delete-banner', function() {
        if (!confirm('Bạn có chắc muốn xóa banner này?')) return;
        var bannerId = $(this).data('id');
        $.ajax({
            url: '{{ route("admin.delete-banner") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', bannerId: bannerId },
            success: function(res) {
                if (res.success) {
                    $('#banners-list').html(res.data);
                    alert(res.message);
                }
            }
        });
    });
});
</script>
