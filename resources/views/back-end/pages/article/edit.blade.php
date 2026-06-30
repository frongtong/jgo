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
                            <form id="form_submit"
                                action="{{ url("$segment/$folder/edit/$data->id") }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div id="kt_app_content_container"
                                    class="app-container container-xxl">

                                    <div class="card">

                                        <div class="card-body">

                                            @if($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif

                                            <div class="row mb-3">

                                                <!-- ชื่อบทความ -->
                                                <div class="col-md-12 mb-3">

                                                    <label class="form-label">
                                                        ชื่อ{{ $moduleTitle ?? 'บทความ' }}
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        name="title"
                                                        value="{{ old('title', $data->title) }}"
                                                        required>

                                                </div>

                                                @if($showCategories ?? true)
                                                <!-- หมวดหลัก -->
                                                <div class="col-md-6 mb-3">

                                                    <label class="form-label">
                                                        หมวดหมู่หลัก
                                                    </label>

                                                    <select
                                                        class="form-select"
                                                        name="article_category1_id"
                                                        id="main_category_id">

                                                        <option value="">
                                                            เลือกหมวดหมู่หลัก
                                                        </option>

                                                        @foreach($mainCategories as $category)

                                                        <option value="{{ $category->id }}"
                                                            {{ old('article_category1_id', $data->article_category1_id) == $category->id ? 'selected' : '' }}>

                                                            {{ $category->name_th }}

                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                <!-- หมวดย่อย -->
                                                <div class="col-md-6 mb-3">

                                                    <label class="form-label">
                                                        หมวดหมู่ย่อย
                                                    </label>

                                                    <select
                                                        class="form-select"
                                                        name="article_category2_id"
                                                        id="sub_category_id">

                                                        <option value="">
                                                            เลือกหมวดหมู่ย่อย
                                                        </option>

                                                        @foreach($subCategories as $sub)

                                                        <option value="{{ $sub->id }}"
                                                            {{ old('article_category2_id', $data->article_category2_id) == $sub->id ? 'selected' : '' }}>

                                                            {{ $sub->name_th }}

                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                @endif

                                                <!-- วันที่เผยแพร่ -->
                                                <div class="col-md-6 mb-3">

                                                    <label class="form-label">
                                                        วันที่เผยแพร่
                                                    </label>

                                                    <input type="datetime-local"
                                                        class="form-control"
                                                        name="published_at"
                                                        value="{{ old('published_at', $data->published_at ? \Carbon\Carbon::parse($data->published_at)->format('Y-m-d\TH:i') : '') }}">

                                                </div>

                                                <div class="col-md-6 mb-3">

                                                    <label class="form-label">
                                                        สถานะ
                                                    </label>

                                                    <select
                                                        class="form-select"
                                                        name="status">

                                                        <option value="on" {{ old('status', $data->status) == 'on' ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>

                                                        <option value="off" {{ old('status', $data->status) == 'off' ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>

                                            <!-- รูปปก -->
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        รูปปก
                                                    </label>

                                                    @if($data->cover_image_url)

                                                    <div class="mb-3">

                                                        <img
                                                            src="{{ asset($data->cover_image_url) }}"
                                                            style="max-width:300px"
                                                            class="img-fluid rounded border">

                                                    </div>

                                                    <p>
                                                        ชื่อไฟล์ :
                                                        <b class="text-danger">
                                                            {{ basename($data->cover_image_url) }}
                                                        </b>
                                                    </p>

                                                    @endif

                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        name="cover_image">

                                                </div>

                                            </div>

                                            <!-- รูป Banner -->
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        รูป Banner {{ ($multipleBanners ?? false) ? '(เพิ่มได้หลายรูป)' : '' }}
                                                    </label>

                                                    @if($multipleBanners ?? false)
                                                    @if($data->banners->isNotEmpty())
                                                    <div class="row g-4 mb-4">
                                                        @foreach($data->banners as $banner)
                                                        <div class="col-md-4">
                                                            <div class="border rounded p-3 h-100">
                                                                <img src="{{ asset($banner->image_url) }}"
                                                                    class="img-fluid rounded mb-3"
                                                                    alt="Banner {{ $loop->iteration }}">
                                                                <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input"
                                                                        type="checkbox"
                                                                        name="remove_banner_ids[]"
                                                                        value="{{ $banner->id }}">
                                                                    <span class="form-check-label text-danger">
                                                                        ลบรูปนี้
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    @endif

                                                    <input type="file"
                                                        class="form-control"
                                                        name="banner_images[]"
                                                        accept=".jpg,.jpeg,.png,.webp"
                                                        multiple>
                                                    <small class="text-muted">
                                                        รูปเดิมจะยังอยู่จนกว่าจะเลือก “ลบรูปนี้”
                                                    </small>
                                                    @else
                                                    @if($data->banner_image_url)

                                                    <div class="mb-3">

                                                        <img
                                                            src="{{ asset($data->banner_image_url) }}"
                                                            style="max-width:500px"
                                                            class="img-fluid rounded border">

                                                    </div>

                                                    <p>
                                                        ชื่อไฟล์ :
                                                        <b class="text-danger">
                                                            {{ basename($data->banner_image_url) }}
                                                        </b>
                                                    </p>

                                                    @endif

                                                    <input
                                                        type="file"
                                                        class="form-control"
                                                        name="banner_image">
                                                    @endif

                                                </div>

                                            </div>

                                            <!-- คำอธิบายสั้น -->
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        คำอธิบายสั้น
                                                    </label>

                                                    <textarea
                                                        class="form-control"
                                                        rows="4"
                                                        name="short_description">{{ old('short_description', $data->short_description) }}</textarea>

                                                </div>

                                            </div>

                                            <!-- รายละเอียด -->
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        รายละเอียด{{ $moduleTitle ?? 'บทความ' }}
                                                    </label>

                                                    <textarea
                                                        id="description"
                                                        name="description">{{ old('description', $data->description) }}</textarea>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-end mt-5">

                                        <a href="{{ url("$segment/$folder") }}"
                                            class="btn btn-light me-2">

                                            Cancel

                                        </a>

                                        <button type="submit"
                                            class="btn btn-primary"
                                            style="background:#1C2842;">

                                            Save Changes

                                        </button>

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
    CKEDITOR.replace('description', {
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
    $('#main_category_id').change(function() {

        let id = $(this).val();

        $.ajax({

            url: "{{ url($segment.'/'.$folder.'/subcategory') }}/" + id,

            type: 'GET',

            success: function(response) {

                let html =
                    '<option value="">เลือกหมวดหมู่ย่อย</option>';

                $.each(response, function(index, item) {

                    html +=
                        '<option value="' + item.id + '">' +
                        item.name_th +
                        '</option>';

                });

                $('#sub_category_id').html(html);

            }

        });

    });
</script>

</html>
