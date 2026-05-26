<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    @include("$prefix.layout.head")
</head>
<!--end::Head-->

<!--begin::Body-->
<style>
    /* Styling for sortable image gallery */
    .sortable-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 10px;
        border: 1px solid #ddd;
        max-width: 100%;
        margin-top: 20px;
    }

    .sortable-gallery .image-container {
        width: 100px;
        height: 100px;
        position: relative;
    }

    .sortable-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    /* Style the delete button as an "X" in the top-right corner */
    .sortable-gallery .delete-button {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        font-size: 16px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        outline: none;
    }
</style>

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
                                    action="{{ route('webpanel.recipe.update', ['id' => $id]) }}">
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
                                                        <div class="intro-y col-12">
                                                            <label class="form-label">หมวดหมู่</label>

                                                            <div class="input-group mb-6">
                                                                <select class="form-select" name="category" value=>
                                                                    <option value="">เลือกประเภทหมวดหมู่</option>
                                                                    @foreach ($category as $item)
                                                                    <option
                                                                        @if ($row->category == $item->id) selected @endif
                                                                        value="{{ $item->id }}">
                                                                        {{ $item->name_th }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <label class="form-label">รูปภาพรองเเบนเนอร์</label>
                                                            <span
                                                                class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ  รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น
                                                                800x800</span>
                                                                <span class="badge badge-light fw-bold fs-8 px-2 py-1 ms-2">{{basename($row->banner_image)}} </span>    
                                                            <div class="input-group mb-10">
                                                                <img src="{{ asset($row->banner_image) }}" alt=""
                                                                    width="100%">
                                                                <input class="form-control" type="file"
                                                                    name="banner">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="row mb-3">
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">ประเภทไฟล์เนื้อหา</label>
                                                                <select id="mediaType" class="form-select"
                                                                    name="media_type" onchange="toggleMediaInput()">
                                                                    <option value="image"
                                                                        {{ $row->type_banner == 'image' ? 'selected' : '' }}>
                                                                        อัปโหลดไฟล์รูปภาพ</option>
                                                                    <option value="video"
                                                                        {{ $row->type_banner == 'video' ? 'selected' : '' }}>
                                                                        อัปโหลดไฟล์วีดีโอ</option>
                                                                    <option value="youtube"
                                                                        {{ $row->type_banner == 'youtube' ? 'selected' : '' }}>
                                                                        URL ของ YouTube</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="fileInput" style="display: none;">
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">รูปภาพ</label>
                                                                @if ($row->type_banner == 'image')
                                                                <span class="badge badge-light fw-bold fs-8 px-2 py-1 ms-2">{{basename($row->video)}} </span> 
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <img src="{{ asset($row->video) }}"
                                                                        class="img-fluid mt-3" alt="Preview" />
                                                                </div>
                                                                @endif
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                                    1600x900 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น</span>
                                                                <div class="input-group mb-10">
                                                                    <input class="form-control" type="file" accept="image/jpg, image/jpeg, image/png"
                                                                        name="image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="videoInput" style="display: none;">
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">วิดีโอ</label>
                                                                @if ($row->type_banner == 'video')
                                                                <div class="intro-y col-span-12 sm:col-span-6">

                                                                    <video class="img-fluid mt-3" controls>
                                                                        <source src="{{ asset($row->video) }}" type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                </div>

                                                                @endif
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">mp4เท่านั้น ขนาดไฟล์ไม่เกิน 200 mb</span>
                                                                <div class="input-group mb-10">
                                                                    <input class="form-control" type="file" accept="video/mp4"
                                                                        name="video" accept="mp4">
                                                                </div>
                                                            </div>
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">ปกวิดีโอ</label>
                                                                @if ($row->type_banner == 'video')
                                                                <span class="badge badge-light fw-bold fs-8 px-2 py-1 ms-2">{{basename($row->cover)}} </span>
                                                                <div class="intro-y col-span-12 sm:col-span-6">

                                                                    <img src="{{ asset($row->cover) }}"
                                                                        class="img-fluid mt-3" alt="Preview" />
                                                                </div>

                                                                @endif
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                                    1600x900 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น</span>
                                                                <div class="input-group mb-10">
                                                                    <input class="form-control" type="file"
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
                                                                        placeholder="กรอก URL ของ YouTube"
                                                                        value="@if($row->type_banner == 'youtube'){{ $row->video }}@endif">
                                                                </div>
                                                            </div>
                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                <label class="form-label">ปกวิดีโอ</label>
                                                                <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ
                                                                    1600x900 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น</span>
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
                                                                        name="title_th_" value="{{ $row->title_th }}">
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
                                                                        name="title_en_" value="{{ $row->title_en }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <span class="input-group-text">รายละเอียด <span
                                                                    class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></span>
                                                            <textarea class="form-control mt-3" id="description_th" rows="5" name="description_th"> {{ $row->description_th }}</textarea>
                                                        </div>
                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                            <span class="input-group-text">รายละเอียด <span
                                                                    class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">EN</span></span>
                                                            <textarea class="form-control mt-3" id="description_en" rows="5" name="description_en">{{ $row->description_en }}</textarea>
                                                        </div>
                                                        <hr>
                                                        {{-- เริ่ม ส่วน สูตรอาหาร --}}
                                                        @foreach($detail as $key_detail => $item)
                                                            <div class="card mb-4">
                                                                <div class="card-header bg-dark">
                                                                    <h5 class="mt-5 text-white">ส่วนสูตรอาหารที่ {{$key_detail+1}}</h5>
                                                                    <button type="button"
                                                                            class="btn btn-danger shadow-md ml-2 mb-3 mt-3"
                                                                            onclick="delete_detail({{$item->id}})">ลบ</button>
                                                                            <input hidden name="id_details[]"
                                                                            value="{{ $item->id }}">  
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="intro-y col-span-12 sm:col-span-6">
                                                                        <label class="form-label">รูปภาพ</label>
                                                                        <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ 380x272 รองรับไฟล์ (jpg, jpeg, png, webp) เท่านั้น</span>
                                                                        <div class="input-group mb-10">
                                                                            <input class="form-control" type="file"
                                                                                accept="image/jpg, image/jpeg, image/png"
                                                                                name="image_details[]">
                                                                               
                                                                            <img src="{{ asset($item->banner) }}" alt="" width="100%">    
                                                                            <span class="badge badge-light fw-bold fs-8 px-2 py-1 ms-2">{{basename($item->banner)}} </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                                            <label class="form-label">
                                                                                <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">ภาษาไทย</span>
                                                                            </label>
                                                                            <div class="input-group mb-5">
                                                                                <span class="input-group-text">หัวข้อ</span>
                                                                                <input class="form-control" type="text" name="title_th[]" value="{{ $item->title_th }}">
                                                                            </div>
                                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                                <span class="input-group-text">รายละเอียด <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">TH</span></span>
                                                                                <textarea class="form-control mt-3" id="details_th{{$item->id}}" rows="5" name="details_th[]">{{ $item->description_th }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                                            <label class="form-label">
                                                                                <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ภาษาอังกฤษ</span>
                                                                            </label>
                                                                            <div class="input-group mb-5">
                                                                                <span class="input-group-text">หัวข้อ</span>
                                                                                <input class="form-control" type="text" name="title_en[]" value="{{ $item->title_en }}">
                                                                            </div>
                                                                            <div class="intro-y col-span-12 sm:col-span-6">
                                                                                <span class="input-group-text">รายละเอียด <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">EN</span></span>
                                                                                <textarea class="form-control mt-3" id="details_en{{$item->id}}" rows="5" name="details_en[]">{{ $item->description_en }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.addEventListener("DOMContentLoaded", function () {
                                                                    document.querySelectorAll("textarea[id^='details_']").forEach(function (textarea) {
                                                                        CKEDITOR.replace(String(textarea.id), {
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
                                                                    });
                                                                });
                                                            </script>

                                                        @endforeach
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-dark">
                                                                <h5 class="mt-5 text-white">สินค้า</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                @foreach ($Recipeproduct as $list_Recipeproduct)
                                                                    <input hidden name="id_Product[]"
                                                                        value="{{ $list_Recipeproduct->id }}">
                                                                    <div class="row mb-3">
                                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                                            <label class="form-label">สินค้า </label>
                                                                            <select class="form-select" name="Product[]">
                                                                                <option value="">เลือกประเภทสินค้า</option>
                                                                                @foreach ($Product as $item_Product)
                                                                                    <option   @if ($list_Recipeproduct->product_id == $item_Product->id) selected @endif  value="{{ $item_Product->id }}">
                                                                                        {{ $item_Product->name_th }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align:right;">

                                                                        <button type="button"
                                                                            class="btn btn-danger shadow-md ml-2 mb-3"
                                                                            onclick="delete_pro({{ $list_Recipeproduct->id }})">ลบ</button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-dark">
                                                                <h5 class="mt-5 text-white">เอกสารอ้างอิง</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                @foreach ($url as $ref)
                                                                    <input hidden name="id_url[]"
                                                                        value="{{ $ref->id }}">
                                                                    <div class="row mb-3">
                                                                        <div class="intro-y col-span-12 sm:col-span-6">
                                                                            <label class="form-label">URL </label>
                                                                            <input  type="text" class="form-control mt-3"
                                                                                id="url" name="url[]"
                                                                                value="{{ $ref->url }}">
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align:right;">

                                                                        <button type="button"
                                                                            class="btn btn-danger shadow-md ml-2 mb-3"
                                                                            onclick="delete_ref({{ $ref->id }})">ลบ</button>
                                                                    </div>
                                                                @endforeach

                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-end mt-5">
                                                                <a href="{{ url("$segment/$folder") }}" id=""
                                                                    class="btn btn-light me-2">Cancel</a>
                                                                <button type="submit" class="btn btn-primary"
                                                                    style="background: #1C2842;"><span
                                                                        class="indicator-label">Save
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
        function delete_path_img(id) {
            console.log(id); // Check if ID is correctly passed
            Swal.fire({
                title: 'คุณต้องการลบไฟล์นี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/webpanel/news_new/destroy/image') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Check the server response
                            if (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบไฟล์สำเร็จ',
                                    text: 'ไฟล์ถูกลบเรียบร้อยแล้ว',
                                    confirmButtonText: 'ปิด',
                                }).then(() => {
                                    location
                                        .reload(); // Reload the page to reflect the deletion
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถลบไฟล์ได้',
                                    confirmButtonText: 'ปิด',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log any error messages from the server
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                                confirmButtonText: 'ปิด',
                            });
                        }
                    });
                }
            });
        }
        function delete_ref(id) {
            console.log(id); // Check if ID is correctly passed
            Swal.fire({
                title: 'คุณต้องการลบเอกสารอ้างอิงนี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/webpanel/recipe/destroy/ref') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Check the server response
                            if (response) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'เอกสารอ้างอิงถูกลบเรียบร้อยแล้ว',
                                    confirmButtonText: 'ปิด',
                                }).then(() => {
                                    location
                                        .reload(); // Reload the page to reflect the deletion
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถลบเอกสารอ้างอิงได้',
                                    confirmButtonText: 'ปิด',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log any error messages from the server
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                                confirmButtonText: 'ปิด',
                            });
                        }
                    });
                }
            });
        }
        function delete_pro(id) {
            console.log(id); // Check if ID is correctly passed
            Swal.fire({
                title: 'คุณต้องการลบสินค้านี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/webpanel/recipe/destroy/pro') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Check the server response
                            if (response) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'สินค้าถูกลบเรียบร้อยแล้ว',
                                    confirmButtonText: 'ปิด',
                                }).then(() => {
                                    location
                                        .reload(); // Reload the page to reflect the deletion
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถลบสินค้าได้',
                                    confirmButtonText: 'ปิด',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log any error messages from the server
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                                confirmButtonText: 'ปิด',
                            });
                        }
                    });
                }
            });
        }
        function delete_detail(id) {
            console.log(id); // Check if ID is correctly passed
            Swal.fire({
                title: 'คุณต้องการลบสูตรอาหารนี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/webpanel/recipe/destroy/detail') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}' // CSRF token for security
                        },
                        success: function(response) {
                            console.log(response); // Check the server response
                            if (response) {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'สูตรอาหารถูกลบเรียบร้อยแล้ว',
                                    confirmButtonText: 'ปิด',
                                }).then(() => {
                                    location
                                        .reload(); // Reload the page to reflect the deletion
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถลบสูตรอาหารได้',
                                    confirmButtonText: 'ปิด',
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText); // Log any error messages from the server
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                                confirmButtonText: 'ปิด',
                            });
                        }
                    });
                }
            });
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
        window.onload = function() {
            toggleMediaInput();
        };
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
    </script>
    <script>
        function check_add() {
            var head_th = $('#head_th').val();
            if (head_th == "") {
                toastr.error("Sorry, please complete the information.");
                return false;
            }
        }
        // Initialize Sortable.js for drag-and-drop functionality
        const sortable = new Sortable(document.getElementById("imageGallery"), {
            animation: 150,
            ghostClass: "sortable-ghost",
        });

        document.getElementById("saveOrderButton").addEventListener("click", function() {
            const orderedIds = Array.from(document.querySelectorAll("#imageGallery .image-container"))
                .map((container, index) => ({
                    id: container.dataset.id,
                    order: index + 1
                }));
            fetch("{{ url('/webpanel/news_new/updateOrder') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        orderedIds
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        console.error("HTTP error:", response.status);
                        return response.text().then(text => {
                            console.error("Server response:", text);
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert("Order updated successfully!");
                    } else {
                        console.error("Server error:", data.error);
                        alert("Failed to update order: " + data.error);
                    }
                })
                .catch(error => {
                    console.error("Fetch error:", error.message);
                    alert("An error occurred: " + error.message);
                });
        });

        // Delete function (assumes an endpoint exists for deleting images)
    </script>
</body>

</html>