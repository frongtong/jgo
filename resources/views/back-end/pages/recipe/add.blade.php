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
                                <form id="form_submit" action="" method="POST" enctype="multipart/form-data"
                                    action="{{ route('webpanel.news_new.add') }}">
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
                                                            <label class="form-label">หมวดหมู่</label>

                                                            <div class="input-group mb-10">
                                                                <select class="form-select" name="category">
                                                                    <option value="">เลือกประเภทหมวดหมู่</option>
                                                                    @foreach ($category as $item)
                                                                        <option value="{{ $item->id }}">
                                                                            {{ $item->name_th }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        

                                                          
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">รูปภาพแบนเนอร์</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                                800x800 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น </span>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file"
                                                                    name="banner"
                                                                    accept="image/jpg, image/jpeg, image/png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">ประเภทไฟล์เนื้อหา</label>
                                                            <select id="mediaType" class="form-select" name="media_type"
                                                                onchange="toggleMediaInput()">
                                                                <option value=""></option>
                                                                <option value="image">อัปโหลดไฟล์รูปภาพ</option>
                                                                <option value="video">อัปโหลดไฟล์วีดีโอ</option>

                                                                <option value="youtube">URL ของ YouTube</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3" id="fileInput" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">รูปภาพ</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ 1600x900
                                                            </span>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file"
                                                                    accept="image/jpg, image/jpeg, image/png"
                                                                    name="image">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3" id="videoInput" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">วิดีโอ</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">mp4เท่านั้น
                                                                ขนาดไฟล์ไม่เกิน 200 mb</span>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file"
                                                                    accept="video/mp4" name="video" accept="mp4">
                                                            </div>
                                                        </div>
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">ปกวิดีโอ</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                                1600x900 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น</span>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file"
                                                                    accept="image/jpg, image/jpeg, image/png"
                                                                    name="cover-video"
                                                                    accept="image/jpg, image/jpeg, image/png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3" id="youtubeInput" style="display: none;">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">URL ของ YouTube</label>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="text"
                                                                    name="youtube_url"
                                                                    placeholder="กรอก URL ของ YouTube">
                                                            </div>
                                                        </div>
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">ปกวิดีโอ</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                            </span>
                                                            <div class="input-group mb-10">
                                                                <input class="form-control" type="file"
                                                                    name="cover-youtube"
                                                                    accept="image/jpg, image/jpeg, image/png">
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">หัวข้อหลัก
                                                                <span
                                                                    class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span>
                                                            </label>
                                                            <div class="input-group mb-10">
                                                                <span class="input-group-text">หัวข้อหลัก</span>
                                                                <input class="form-control" type="text"
                                                                    name="title_th_">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">หัวข้อหลัก
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span>
                                                            </label>
                                                            <div class="input-group mb-10">
                                                                <span class="input-group-text">หัวข้อหลัก</span>
                                                                <input class="form-control" type="text"
                                                                    name="title_en_">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>


                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <span class="input-group-text">รายละเอียด <span
                                                                class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></span>
                                                        <textarea class="form-control mt-3" id="description_th" rows="5" name="description_th"></textarea>
                                                    </div>
                                                    <hr>
                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                        <span class="input-group-text">รายละเอียด <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span></span>
                                                        <textarea class="form-control mt-3" id="description_en" rows="5" name="description_en"></textarea>
                                                    </div>
                                                    <br>

                                                    {{-- เริ่ม ส่วน สูตรอาหาร --}}
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-dark">
                                                            <h5 class="mt-5 text-white">ส่วนสูตรอาหารที่ 1</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">รูปภาพ</label>
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ 380x272 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น
                                                                </span>
                                                                <div class="input-group mb-10">
                                                                    <input class="form-control" type="file"
                                                                        accept="image/jpg, image/jpeg, image/png"
                                                                        name="image_details[]">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label class="form-label">
                                                                        <span
                                                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">ภาษาไทย</span>
                                                                    </label>
                                                                    <div class="input-group mb-5">
                                                                        <span class="input-group-text">หัวข้อ</span>
                                                                        <input class="form-control" type="text"
                                                                            name="title_th[]">
                                                                    </div>
                                                                   
                                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                                        <span class="input-group-text">รายละเอียด <span
                                                                                class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></span>
                                                                                <textarea class="form-control mt-3" id="details_th" rows="5" name="details_th[]"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label class="form-label">
                                                                        <span
                                                                            class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ภาษาอังกฤษ</span>
                                                                    </label>
                                                                    <div class="input-group mb-5">
                                                                        <span class="input-group-text">หัวข้อ</span>
                                                                        <input class="form-control" type="text"
                                                                            name="title_en[]">
                                                                    </div>
                                                                  
                                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                                        <span class="input-group-text">รายละเอียด <span
                                                                                class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">EN</span></span>
                                                                                <textarea class="form-control mt-3" id="details_en" rows="5" name="details_en[]"></textarea>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div id="form-container-Refer"></div>
                                                            <div class="col-md-9 mb-5">
                                                                <button id="add-form-refer-btn" type="button"
                                                                    class="btn btn-dark shadow-md ml-2 mb-3">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- จบ ส่วน สูตรอาหาร --}}
                                                   
                                                    <br>
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-dark">
                                                            <h5 class="mt-5 text-white">สินค้า</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6"> 
                                                                    <div class="input-group mb-10">
                                                                        <span class="input-group-text">สินค้า</span>
                                                                            <select class="form-select" name="Product[]">
                                                                                <option value="">เลือกประเภทสินค้า</option>
                                                                                @foreach ($Product as $item_Product)
                                                                                    <option value="{{ $item_Product->id }}">
                                                                                        {{ $item_Product->name_th }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="form-pro-Refer"></div>
                                                            <div class="col-md-9 mb-5">
                                                                <button id="add-pro-btn" type="button"
                                                                    class="btn btn-dark shadow-md ml-2 mb-3">+</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="card mb-4">
                                                        <div class="card-header bg-dark">
                                                            <h5 class="mt-5 text-white">เอกสารอ้างอิง</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <div class="input-group mb-10">
                                                                        <span class="input-group-text">URL</span>
                                                                        <input class="form-control" type="text"
                                                                            name="url[]">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <div class="input-group mb-10">
                                                                        <span
                                                                            class="input-group-text">ข้อความอ้างอิง</span>
                                                                        <input class="form-control" type="text"
                                                                            name="text_ref[]" id="text_ref">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="form-ref-Refer"></div>
                                                            <div class="col-md-9 mb-5">
                                                                <button id="add-ref-btn" type="button"
                                                                    class="btn btn-dark shadow-md ml-2 mb-3">+</button>
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
                                                </div>
                                               
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
        let formCountRef = 0;
        const formContainerRef = document.getElementById("form-ref-Refer");

        document.getElementById("add-ref-btn").addEventListener("click", function() {
            formCountRef++;
            const divRef = document.createElement("div");
            divRef.setAttribute("id", `refer${formCountRef}`);
            divRef.innerHTML = `<hr>
                <div class="row mb-3">
                    <div class="intro-y col-span-12 sm:col-span-6"> 
                        <div class="input-group mb-10">
                            <span
                                class="input-group-text">URL</span>
                            <input class="form-control" type="text"
                                name="url[]" id="url${formCountRef}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <div class="input-group mb-10">
                                <span
                                    class="input-group-text">ข้อความอ้างอิง</span>
                                <input class="form-control" type="text"
                                    name="text_ref[]" id="text_ref">
                            </div>
                        </div>
                    </div>
                </div>
            <button type="button" class="btn btn-danger shadow-md ml-2 mt-3 mb-3" onclick="del_Ref(${formCountRef})">-</button>
        `;
            formContainerRef.appendChild(divRef);
        });

        function del_Ref(index) {
            const divRef = document.getElementById(`refer${index}`);
            if (divRef) {
                if (confirm(`Are you sure you want to delete Ref ${index}?`)) {
                    formContainerRef.removeChild(divRef);
                    formCountRef--;
                }
            }
        }
    </script>
    <script>
        let formCountRefer = 0;
        const formContainerRefer = document.getElementById("form-container-Refer");

        // Initialize the visibility of the "ชื่อไฟล์" input based on the initial form's selection
        document.querySelectorAll('.form-select').forEach((select, index) => {
            toggleNameFileInput(index);
        });

        // document.getElementById("add-form-refer-btn").addEventListener("click", function() {
        //     formCountRefer++;
        //     const divRefer = document.createElement("div");
        //     divRefer.setAttribute("id", `refer${formCountRefer}`);
        //     divRefer.innerHTML = `
        //     <div class="row">
        //         <div class="intro-y col-span-12 sm:col-span-6">
        //             <label class="form-label">อัพโหลดไฟล์</label>
        //             <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ800x800</span>
        //             <div class="input-group mb-10">
        //                 <input class="form-control" type="file"  name="path[]" id="path${formCountRefer}" multiple accept="image/jpg, image/jpeg, image/png">
        //             </div>
        //         </div>                


        //     </div>
        //     <button type="button" class="btn btn-danger shadow-md ml-2 mt-3 mb-3" onclick="del_Refer(${formCountRefer})">-</button>
        // `;
        //     formContainerRefer.appendChild(divRefer);
        // });


        function toggleNameFileInput(index) {
            const namefileContainer = document.getElementById(`namefile-container${index}`);
            const fileTypeSelect = document.getElementById(`type${index}`);

            if (namefileContainer && fileTypeSelect) {
                namefileContainer.style.display = fileTypeSelect.value === "file" ? "block" : "none";
            }
        }

        function del_Refer(index) {
            const divRefer = document.getElementById(`refer${index}`);
            if (divRefer) {
                if (confirm(`Are you sure you want to delete item ${index}?`)) {
                    formContainerRefer.removeChild(divRefer);
                    formCountRefer--;
                }
            }
        }

        function toggleMediaInput() {
            var mediaType = document.getElementById("mediaType").value;
            var fileInput = document.getElementById("fileInput");
            var youtubeInput = document.getElementById("youtubeInput");
            var videoInput = document.getElementById("videoInput");

            if (mediaType === "image") {
                fileInput.style.display = "block";
                videoInput.style.display = "none";
                youtubeInput.style.display = "none";
            } else if (mediaType === "youtube") {
                fileInput.style.display = "none";
                videoInput.style.display = "none";
                youtubeInput.style.display = "block";
            } else if (mediaType === "video") {
                fileInput.style.display = "none";
                videoInput.style.display = "block";
                youtubeInput.style.display = "none";

            }
        }
    </script>
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
        CKEDITOR.replace('details_en', {
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
        CKEDITOR.replace('details_th', {
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
