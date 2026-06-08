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
    action=""
    method="POST"
    enctype="multipart/form-data">

    @csrf

    <div id="kt_app_content_container"
        class="app-container container-xxl">

        <div class="card">

            <div class="card-body">

                <div class="card-title text-center py-3">
                </div>

                <div class="container-fluid">

                    <div class="row mb-3">

                        <!-- ชื่อบทความ -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                ชื่อบทความ
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                class="form-control"
                                name="title"
                                value="{{ $data->title }}"
                                required>

                        </div>

                        <!-- หมวดหมู่หลัก -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                หมวดหมู่หลัก
                            </label>

                            <select
                                class="form-select"
                                name="main_category_id"
                                id="main_category_id">

                                <option value="">
                                    เลือกหมวดหมู่หลัก
                                </option>

                                @foreach($mainCategories as $category)

                                    <option value="{{ $category->id }}"
                                        {{ $data->main_category_id == $category->id ? 'selected' : '' }}>

                                        {{ $category->name_th }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- หมวดหมู่ย่อย -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                หมวดหมู่ย่อย
                            </label>

                            <select
                                class="form-select"
                                name="sub_category_id"
                                id="sub_category_id">

                                <option value="">
                                    เลือกหมวดหมู่ย่อย
                                </option>

                                @foreach($subCategories as $sub)

                                    <option value="{{ $sub->id }}"
                                        {{ $data->sub_category_id == $sub->id ? 'selected' : '' }}>

                                        {{ $sub->name_th }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- วันที่เผยแพร่ -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                วันที่เผยแพร่
                            </label>

                            <input type="datetime-local"
                                class="form-control"
                                name="published_at"
                                value="{{ $data->published_at ? \Carbon\Carbon::parse($data->published_at)->format('Y-m-d\TH:i') : '' }}">

                        </div>

                    </div>

                    <!-- รูปปก -->
                    <div class="row mb-3">

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
                                name="cover_image"
                                accept=".jpg,.jpeg,.png,.webp">

                        </div>

                    </div>

           <!-- Youtube URL -->
<div class="col-md-12 mb-5">

    <label class="form-label">
        ลิงก์ Youtube
        <span class="text-danger">*</span>
    </label>

    <input
        type="url"
        class="form-control"
        name="youtube_url"
        value="{{ $data->youtube_url }}"
        placeholder="https://www.youtube.com/watch?v=xxxxxxxx">

   @if(!empty($data->youtube_url))

    @php

        $videoId = '';

        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $data->youtube_url, $matches)) {

            $videoId = $matches[1];

        } elseif (preg_match('/youtu\.be\/([^?]+)/', $data->youtube_url, $matches)) {

            $videoId = $matches[1];

        }

    @endphp

    @if($videoId)

        <div class="col-md-12 mb-5">

            <label class="form-label">
                ตัวอย่างวิดีโอ
            </label>

            <div class="ratio ratio-16x9">

                <iframe
                    src="https://www.youtube.com/embed/{{ $videoId }}"
                    title="Youtube Video"
                    allowfullscreen>
                </iframe>

            </div>

        </div>

    @endif

@endif

</div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="d-flex justify-content-end mt-5">

                    <a href="{{ url("$segment/$folder") }}"
                        class="btn btn-light me-2">

                        Cancel

                    </a>

                    <button type="submit"
                        class="btn btn-primary"
                        style="background:#1C2842;">

                        <span class="indicator-label">

                            Save Changes

                        </span>

                    </button>

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
