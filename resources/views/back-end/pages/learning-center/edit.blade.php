<!DOCTYPE html>
<html lang="en">

<head>
    @include("$prefix.layout.head")
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header" data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
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
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <form action="{{ url("$segment/$folder") }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-9 mb-5">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>ศูนย์การเรียนรู้</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body pt-0">
                                                    <div class="row mb-5">
                                                        <div class="col-md-12">
                                                            <label class="form-label">สถานะ</label>
                                                            <select class="form-select" name="status">
                                                                <option value="on" {{ old('status', $data->status) == 'on' ? 'selected' : '' }}>เปิดใช้งาน</option>
                                                                <option value="off" {{ old('status', $data->status) == 'off' ? 'selected' : '' }}>ปิดใช้งาน</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-12">
                                                            <label class="form-label">รูปภาพแบนเนอร์</label>
                                                            <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">แบนเนอร์ 1 รูป</span>
                                                            <small class="help-block"> * รองรับไฟล์ <strong class="text-danger">(jpg, jpeg, png, webp)</strong> เท่านั้น</small>

                                                            @if($data->image_url)
                                                                <div class="my-3">
                                                                    <img src="{{ asset($data->image_url) }}" style="max-width: 720px; width: 100%;" class="img-fluid rounded border" alt="Learning Center Banner">
                                                                </div>
                                                                <p>ชื่อไฟล์ : <b class="text-danger">{{ basename($data->image_url) }}</b></p>
                                                            @endif

                                                            <input type="file" class="form-control" name="banner_image" accept="image/*">

                                                            @error('banner_image')
                                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-5">
                                        <button type="submit" class="btn btn-primary" style="background:#1C2842;">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="kt_app_footer" class="app-footer">
                        @include("$prefix.layout.footer")
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    @include("$prefix.layout.script")
</body>

</html>
