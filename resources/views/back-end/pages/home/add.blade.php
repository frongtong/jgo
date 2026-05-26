<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    @include("$prefix.layout.head")
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    class="app-default">
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header" data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                @include("$prefix.layout.head-menu")
            </div>
            <!--end::Header-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!--begin::Sidebar-->
                @include("$prefix.layout.side-menu")
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                                @include("$prefix.layout.breadcrumbs")
                            </div>
                        </div>

                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <form id="form_submit" action="" method="POST" enctype="multipart/form-data" onsubmit="return check_add();">
                                @csrf
                                    <div class="row">
                                        <div class="col-md-9 mb-5">
                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>บทความ</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-6">
                                                            <label class="form-label">ประเภทพื้นหลัง</label>
                                                            <div class="input-group mb-10">
                                                                <select class="form-select" name="category"
                                                                    id="categorySelect">
                                                                    <option value="">ประเภทพื้นหลัง</option>
                                                                    <option value="img_bg">รูปภาพ</option>
                                                                    <option value="video_bg">ไฟล์ video</option>
                                                                    <option value="url_bg">URL</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Image background input -->
                                                    <div class="row mb-3" id="imgBgField" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">รูปภาพเเบนเนอร์</label>
                                                            <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ 1600x800</span> <small class="help-block"> * รองรับไฟล์ <strong class="text-danger">(jpg, jpeg, png, webp)</strong> เท่านั้น</small>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file" name="img_bg" accept="image/*">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Video background input -->
                                                    <div class="row mb-3" id="videoBgField" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">วิดีโอเเบนเนอร์</label> <small class="help-block"><strong class="text-danger"> * รองรับไฟล์ (mp4, mov, avi, mkv) เท่านั้น, ขนาดไฟล์ต้องไม่เกิน 100 MB</strong></small>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file" name="video_bg" accept="video/*">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- URL background input -->
                                                    <div class="row mb-3" id="urlBgField" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">URL</label>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="text"
                                                                    name="url_bg">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                   <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">หัวข้อ<span
                                                                    class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span> <small class="text-danger">กรุณาเลือก</small></label>
                                                            <div class="input-group mb-10">
                                                                <textarea class="form-control" name="title_th" rows="1"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                      <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">หัวข้อ<span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span> <small class="text-danger">กรุณาเลือก</small></label>
                                                            <div class="input-group mb-10">
                                                                <textarea class="form-control" name="title_en" rows="1"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">ข้อความ<span
                                                                    class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></label>
                                                            <div class="input-group mb-10">
                                                                <textarea class="form-control" name="message_th" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">ข้อความ<span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span></label>
                                                            <div class="input-group mb-10">
                                                                <textarea class="form-control" name="message_en" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">URL เชื่อมหน้า</label>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="text"
                                                                    name="link">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-end mt-5">
                                                <a href="{{ url("$segment/$folder") }}" id=""
                                                    class="btn btn-light me-2">Cancel</a>
                                                <button type="submit" class="btn btn-primary"
                                                    style="background: #1C2842;"><span class="indicator-label">Save
                                                        Changes</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end::Content container-->
                </div>

            </div>
            <!--end::Content wrapper-->

            <!--begin::Footer-->
            <div id="kt_app_footer" class="app-footer">
                @include("$prefix.layout.footer")
            </div>
            <!--End::Footer-->
        </div>
        <!--End::Main-->
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <script>
        CKEDITOR.replace('message_th', {
            versionCheck: false,
            height: 500, 
            filebrowserUploadMethod: 'form',
        });

        CKEDITOR.replace('title_th', {
            versionCheck: false,
            height: 100, 
            filebrowserUploadMethod: 'form',
        });
            CKEDITOR.replace('message_en', {
            versionCheck: false,
            height: 500, 
            filebrowserUploadMethod: 'form',
        });

        CKEDITOR.replace('title_en', {
            versionCheck: false,
            height: 100, 
            filebrowserUploadMethod: 'form',
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#categorySelect').change(function() {
                var selectedValue = $(this).val();

                // Hide all fields
                $('#imgBgField').hide();
                $('#videoBgField').hide();
                $('#urlBgField').hide();

                // Show the relevant field based on the selected value
                if (selectedValue === 'img_bg') {
                    $('#imgBgField').show();
                } else if (selectedValue === 'video_bg') {
                    $('#videoBgField').show();
                } else if (selectedValue === 'url_bg') {
                    $('#urlBgField').show();
                }
            });
        });
    </script>
    <script>
        function check_add() {
            var head_th = $('#head_th').val();
            if (head_th == "") {
                toastr.error("Sorry, please complete the information.");
                return false;
            }
        }
    </script>
</body>

</html>
