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
                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                        @include("$prefix.layout.breadcrumbs")
                    </div>
                </div>
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content  flex-column-fluid ">
                            <form id="form_submit" action="" method="POST" enctype="multipart/form-data">
                                {{-- <form id="form_submit" action="{{ route('webpanel.category1.add') }}" method="POST" enctype="multipart/form-data"> --}}
                                @csrf
                                <div id="kt_app_content_container" class="app-container  container-xxl ">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title text-center py-3">
                                                <!-- <h5>จังหวัด</h5> -->
                                            </div>
                                            <div class="container-fluid">
                                                <div class="row mb-3">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">ชื่อ<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="ชื่อ" name="name" id="name" required>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">สังกัดจังหวัด (ถ้าเป็นอำเภอให้เลือกจังหวัด)</label>
                                                    <select name="parent_id" class="form-select form-select-solid">
                                                        <option value="">-- ไม่ระบุ (เป็นจังหวัด) --</option>
                                                        @foreach($parents as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted">* เว้นว่างไว้หากรายการนี้คือจังหวัด</small>
                                                </div>
                                            </div>
                                            <!-- <div class="row mb-3">
                                                <div class="intro-y col-span-12 sm:col-span-6">

                                                    <span class="input-group-text">รายละเอียด <span
                                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></span>
                                                    <textarea class="form-control mt-3" id="description_th" rows="5" name="description_th"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="intro-y col-span-12 sm:col-span-6">

                                                    <span class="input-group-text">รายละเอียด <span
                                                            class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span></span>

                                                    <textarea class="form-control mt-3" id="description_en" rows="5" name="description_en"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                    <label class="form-label">รูปภาพ</label>
                                                    <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ 1174x766</span> <small class="help-block"> * รองรับไฟล์ <strong class="text-danger">(jpg, jpeg, png, webp)</strong> เท่านั้น</small>
                                                    <div class="input-group mb-10">
                                                        <input class="form-control" type="file" name="image" accept="image/jpg, image/jpeg, image/png">
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-end mt-5">
                                                <a href="{{ url("$segment/$folder") }}" id="" class="btn btn-light me-2">Cancel</a>
                                                <button type="submit" class="btn btn-primary" style="background: #1C2842;"><span class="indicator-label">Save Changes</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
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

        </div>
    </div>
    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <!--end::Javascript-->

</body>
<!--end::Body-->
<script>
    CKEDITOR.replace('description_th', {
        fullPage: true,
        allowedContent: true,
        height: 320,
        versionCheck: false,
        extraPlugins: 'uploadimage',
        filebrowserBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html',
        filebrowserImageBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html?type=Images',
        filebrowserUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Images',
        removeButtons: 'PasteFromWord'
    });
    CKEDITOR.replace('description_en', {
        fullPage: true,
        allowedContent: true,
        height: 320,
        versionCheck: false,
        extraPlugins: 'uploadimage',
        filebrowserBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html',
        filebrowserImageBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html?type=Images',
        filebrowserUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Images',
        removeButtons: 'PasteFromWord'
    });
</script>

</html>