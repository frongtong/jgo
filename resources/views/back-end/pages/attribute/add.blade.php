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
                        <div id="kt_app_content" class="app-content  flex-column-fluid ">
                            <div id="kt_app_content_container" class="app-container  container-xxl ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title text-center py-3">
                                            <h5>คุณสมบัติ</h5>
                                        </div>
                                        <form id="form_submit" action="{{ route('webpanel.attribute.add') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="container-fluid">
                                                <div class="row mb-3">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ชื่อ_ไทย<span
                                                                class="text-danger">*</span></label>
                                                        <input name="name_th" type="text" class="form-control"
                                                            placeholder="ชื่อ_ไทย">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ชื่อ_อังกฤษ</label>
                                                        <input name="name_en" type="text" class="form-control"
                                                            placeholder="ชื่อ_อังกฤษ">
                                                    </div>
                                                </div>
                                                {{-- จบ บทความที่ 1 --}}
                                                <div class="row mb-3">
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <label class="form-label">รูปภาพ</label>
                                                        <span
                                                            class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                            121x121</span>
                                                        <div class="input-group mb-10">
                                                            <input class="form-control" type="file" name="logo" accept="image/jpg, image/jpeg, image/png">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-right mt-5">
                                                    <a class="btn btn-outline-secondary w-24 mr-1"
                                                        href="{{ url("$folder") }}">ยกเลิก</a>
                                                    <button type="submit"
                                                        class="btn btn-primary w-24">บันทึกข้อมูล</button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
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

        </div>
    </div>
    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <!--end::Javascript-->

</body>
<!--end::Body-->
<script>
            let formCountUrl = 0;

    document.getElementById("addFormUrl").addEventListener("click", function() {
        formCountUrl++;

        const divUrl = document.createElement("div");
        divUrl.setAttribute("id", `url${formCountUrl}`);
        divUrl.classList.add('mb-3');

        divUrl.innerHTML = `
            
        <div class="input-group mb-3">
            <div class="col-md-12 mb-3">
                <label class="form-label">URL<span
                class="text-danger">*</span></label>
            <input type="text" class="form-control mr-2" name="urls[]" placeholder="Enter URL" required />
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label">รูปแพลตฟอร์ม<span
            class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
            800x800</span></label>
            <input type="file" class="form-control" name="images[]" accept="image/jpg, image/jpeg, image/png" required />
            </div>


            <button type="button" class="btn btn-danger" onclick="removeFormUrl(${formCountUrl})">Remove</button>
        </div>`;

        document.getElementById("form-container-Url").appendChild(divUrl);
    });
    function removeFormUrl(num) {
            const divUrl = document.getElementById(`url${num}`);
            if (divUrl) {
                divUrl.remove();
            }
        }
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
