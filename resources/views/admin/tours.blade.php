@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý <small>Tours</small></h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Tours</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive">
                                            <p class="text-muted font-13 m-b-30">
                                                Chào mừng bạn đến với trang quản lý tour. Tại đây, bạn có thể thêm mới,
                                                chỉnh sửa, và quản lý tất cả các tour hiện có.
                                            </p>
                                            <div class="import-excel-section" style="margin-bottom: 20px; padding: 15px; background: #f7f9fc; border-radius: 8px; border: 1px solid #e1e8f0;">
                                                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                                    <span style="font-weight: 600; color: #2c3e50; font-size: 14px;">
                                                        <i class="fa fa-file-excel-o" style="color: #27ae60;"></i> Nhập Tour từ Excel:
                                                    </span>
                                                    <a href="{{ route('admin.download-tour-template') }}" class="btn btn-success btn-sm" style="border-radius: 20px; padding: 6px 18px;">
                                                        <i class="fa fa-download"></i> Tải file mẫu
                                                    </a>
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#import-excel-modal" style="border-radius: 20px; padding: 6px 18px;">
                                                        <i class="fa fa-upload"></i> Import Excel
                                                    </button>
                                                </div>
                                            </div>
                                            <table id="datatable-listTours" class="table table-striped table-bordered"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tên</th>
                                                        <th>Thời gian</th>
                                                        <th>Mô tả</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá người lớn</th>
                                                        <th>Giá trẻ em</th>
                                                        <th>Điểm đến</th>
                                                        <th>Khả dụng</th>
                                                        <th>Ngày bắt đầu</th>
                                                        <th>Ngày kết thúc</th>
                                                        <th>Sửa</th>
                                                        <th>Xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-listTours">
                                                    @include('admin.partials.list-tours')
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
        <!-- Modal Edit Tour-->
        <div class="modal fade" id="edit-tour-modal" tabindex="-1" role="dialog" aria-labelledby="edit-tour-Label"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-tour-Label">Chỉnh sửa Tour</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="wizard" class="form_wizard wizard_horizontal wizard-edit-tour">
                            <ul class="wizard_steps">
                                <li>
                                    <a href="#step-1">
                                        <span class="step_no">1</span>
                                        <span class="step_descr">
                                            Bước 1<br />
                                            <small>Bước 1 Nhập thông tin </small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step-2">
                                        <span class="step_no">2</span>
                                        <span class="step_descr">
                                            Bước 2<br />
                                            <small>Bước 2 Thêm hình ảnh</small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#step-3">
                                        <span class="step_no">3</span>
                                        <span class="step_descr">
                                            Bước 3<br />
                                            <small>Bước 3 Lộ trình</small>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <div id="step-1">
                                <form class="form-info-tour" method="POST"
                                    id="form-step1">
                                    @csrf
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Tên
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" name="name" placeholder="Nhập tên Tour"
                                                required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Điểm đến
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" name="destination" placeholder="Điểm đến"
                                                required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Khu
                                            vực<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="domain" id="domain">
                                                <option value="">Chọn khu vực</option>
                                                <option value="b">Miền Bắc</option>
                                                <option value="t">Miền Trung</option>
                                                <option value="n">Miền Nam</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Số lượng
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="number" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Giá người
                                            lớn
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="price_adult" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Giá trẻ em
                                            <span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" type="number" name="price_child" required>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày khởi
                                            hành<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control datetimepicker" id="start_date"
                                                name="start_date" disabled>
                                        </div>
                                    </div>
                                    <div class="field item form-group">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Ngày kết
                                            thúc<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control datetimepicker" id="end_date"
                                                name="end_date" disabled>
                                        </div>
                                    </div>

                                    <div class="field item form-group bad">
                                        <label class="col-form-label col-md-3 col-sm-3  label-align">Mô
                                            tả<span>*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <textarea name="description" id="description" rows="10" required></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div style="margin-top: 15px; text-align: right;">
                                    <button type="button" id="btn-quick-finish" class="btn btn-primary" style="padding: 8px 25px; font-size: 14px;">
                                        <i class="fa fa-check"></i> Hoàn thành nhanh
                                    </button>
                                </div>

                            </div>
                            <div id="step-2">
                                <h2 class="StepTitle">Thêm hình ảnh</h2>
                                <form action="" class="dropzone dz-clickable"
                                    id="myDropzone-listTour" enctype="multipart/form-data">
                                    @csrf
                                    <div class="dz-default dz-message">
                                        <span>Chọn hình ảnh về tours để upload</span>
                                    </div>
                                </form>
                            </div>
                            <form action="{{ route('admin.edit-tour') }}" id="timeline-form" method="POST">
                                @csrf
                                <input type="hidden" name="tourId" class="hiddenTourId">
                                <div id="step-3">
                                    <h2 class="StepTitle">Nhập lộ trình</h2>

                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Import Excel -->
<div class="modal fade" id="import-excel-modal" tabindex="-1" role="dialog" aria-labelledby="import-excel-Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2E86C1, #1a5276); color: white;">
                <h5 class="modal-title" id="import-excel-Label">
                    <i class="fa fa-file-excel-o"></i> Nhập Tour từ file Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Upload Area -->
                <div id="import-upload-area" style="border: 2px dashed #bdc3c7; border-radius: 12px; padding: 40px 20px; text-align: center; cursor: pointer; transition: all 0.3s; background: #fafbfc;">
                    <input type="file" id="import-file-input" accept=".xlsx,.xls,.csv" style="display:none;">
                    <div id="import-drop-icon">
                        <i class="fa fa-cloud-upload" style="font-size: 48px; color: #2E86C1; margin-bottom: 15px;"></i>
                        <h4 style="color: #34495e; margin-bottom: 8px;">Kéo thả file Excel vào đây</h4>
                        <p style="color: #7f8c8d; margin-bottom: 15px;">hoặc click để chọn file</p>
                        <span class="btn btn-outline-primary btn-sm" style="border: 1px solid #2E86C1; color: #2E86C1; border-radius: 20px; padding: 6px 20px;">
                            <i class="fa fa-folder-open"></i> Chọn file
                        </span>
                        <p style="color: #95a5a6; font-size: 12px; margin-top: 12px;">
                            Hỗ trợ: .xlsx, .xls, .csv
                        </p>
                    </div>
                    <div id="import-file-info" style="display: none;">
                        <i class="fa fa-file-excel-o" style="font-size: 36px; color: #27ae60; margin-bottom: 10px;"></i>
                        <p id="import-file-name" style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;"></p>
                        <p id="import-file-size" style="color: #7f8c8d; font-size: 13px;"></p>
                        <a href="javascript:void(0)" id="import-file-remove" style="color: #e74c3c; font-size: 13px;">
                            <i class="fa fa-times"></i> Xóa file
                        </a>
                    </div>
                </div>

                <!-- Progress -->
                <div id="import-progress" style="display: none; margin-top: 20px;">
                    <div style="text-align: center; padding: 20px;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 32px; color: #2E86C1;"></i>
                        <p style="margin-top: 10px; color: #34495e; font-weight: 500;">Đang xử lý import...</p>
                    </div>
                </div>

                <!-- Results -->
                <div id="import-results" style="display: none; margin-top: 20px;">
                    <div id="import-success-box" style="display: none; background: #eafaf1; border: 1px solid #27ae60; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <h5 style="color: #27ae60; margin: 0;">
                            <i class="fa fa-check-circle"></i>
                            <span id="import-success-text"></span>
                        </h5>
                    </div>

                    <div id="import-errors-box" style="display: none; background: #fef5f5; border: 1px solid #e74c3c; border-radius: 8px; padding: 15px;">
                        <h5 style="color: #e74c3c; margin-bottom: 10px;">
                            <i class="fa fa-exclamation-triangle"></i>
                            <span id="import-errors-title"></span>
                        </h5>
                        <div style="max-height: 250px; overflow-y: auto;">
                            <table class="table table-condensed" style="margin-bottom: 0; font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Dòng</th>
                                        <th>Lỗi</th>
                                    </tr>
                                </thead>
                                <tbody id="import-errors-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btn-start-import" disabled>
                    <i class="fa fa-upload"></i> Bắt đầu Import
                </button>
            </div>
        </div>
    </div>
</div>
@include('admin.blocks.footer')
