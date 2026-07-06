<!DOCTYPE html>
<html lang="en">
<head>
    @include("$prefix.layout.head")
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header">
                @include("$prefix.layout.head-menu")
            </div>
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include("$prefix.layout.side-menu")
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                                @include("$prefix.layout.breadcrumbs")
                            </div>
                        </div>
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
                                @csrf
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="card">
                                        <div class="card-header border-0 pt-6">
                                            <div class="card-title">
                                                <h3 class="fw-bold">แจ้งเตือนรวม</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    @foreach ($errors->all() as $error)
                                                        <div>{{ $error }}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12 mb-5">
                                                    <label class="form-label required">หัวข้อแจ้งเตือน</label>
                                                    <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" required>
                                                </div>
                                                <div class="col-md-12 mb-5">
                                                    <label class="form-label">รายละเอียดแจ้งเตือน</label>
                                                    <textarea name="detail" class="form-control" rows="5">{{ old('detail', $data->detail) }}</textarea>
                                                </div>
                                                <div class="col-md-6 mb-5">
                                                    <label class="form-label">ภาพปก</label>
                                                    <input type="file" name="cover_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                    @if($data->cover_image)
                                                        <div class="mt-3">
                                                            <img src="{{ asset($data->cover_image) }}" class="rounded" style="max-width: 180px;">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-5">
                                                    <label class="form-label">ภาพเนื้อหา</label>
                                                    <input type="file" name="content_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                    @if($data->content_image)
                                                        <div class="mt-3">
                                                            <img src="{{ asset($data->content_image) }}" class="rounded" style="max-width: 180px;">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label required">วันที่เริ่มแจ้งเตือน</label>
                                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($data->start_date)->format('Y-m-d')) }}" required>
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label required">วันที่สิ้นสุดแจ้งเตือน</label>
                                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($data->end_date)->format('Y-m-d')) }}" required>
                                                </div>
                                                <div class="col-md-4 mb-5">
                                                    <label class="form-label">สถานะ</label>
                                                    <select name="status" class="form-select">
                                                        <option value="on" {{ old('status', $data->status) == 'on' ? 'selected' : '' }}>เปิดใช้งาน</option>
                                                        <option value="off" {{ old('status', $data->status) == 'off' ? 'selected' : '' }}>ปิดใช้งาน</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-5">
                                                <a href="{{ url("$segment/$folder") }}" class="btn btn-light me-2">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("$prefix.layout.script")
</body>
</html>
