<!DOCTYPE html>
<html lang="en">

<head>
    @include("$prefix.layout.head")
</head>

<body id="kt_app_body"
    data-kt-app-layout="dark-sidebar"
    data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true"
    data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true"
    data-kt-app-toolbar-enabled="true"
    class="app-default">

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">

        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <!-- HEADER -->
            <div id="kt_app_header"
                class="app-header"
                data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}"
                data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}"
                data-kt-sticky-animation="false">

                @include("$prefix.layout.head-menu")

            </div>

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!-- SIDEBAR -->
                @include("$prefix.layout.side-menu")

                <!-- TOOLBAR -->
                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                    <div id="kt_app_toolbar_container"
                        class="app-container container-xxl d-flex flex-stack">

                        @include("$prefix.layout.breadcrumbs")

                    </div>

                </div>

                <!-- MAIN -->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                    <div class="d-flex flex-column flex-column-fluid">

                        <div id="kt_app_content"
                            class="app-content flex-column-fluid">

                            <form id="form_submit"
                                action="{{ url("$segment/$folder/add") }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div id="kt_app_content_container"
                                    class="app-container container-xxl">

                                    <div class="card">

                                        <div class="card-body">

                                            <div class="card-title text-center py-3">

                                                <h3 class="fw-bold">

                                                    เพิ่มข้อมูลบริษัท

                                                </h3>

                                            </div>

                                            <div class="container-fluid">

                                                <div class="row">

                                                    {{-- NAME TH --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            ชื่อบริษัท (TH)

                                                            <span class="text-danger">*</span>

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="name_th"
                                                            placeholder="ชื่อบริษัท"
                                                            required>

                                                    </div>



                                                    {{-- NAME EN --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            ชื่อบริษัท (EN)

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="name_en"
                                                            placeholder="Company Name">

                                                    </div>



                                                    {{-- NAME JP --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            ชื่อบริษัท (JP)

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="name_jp"
                                                            placeholder="会社名">

                                                    </div>



                                                    {{-- WEBSITE --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            Website

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="website"
                                                            placeholder="https://example.com">

                                                    </div>



                                                    {{-- PROVINCE --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            จังหวัด

                                                        </label>

                                                        <select class="form-select"
                                                            name="province_id"
                                                            id="province_id">

                                                            <option value="">

                                                                เลือกจังหวัด

                                                            </option>

                                                            @foreach($provinces as $province)

                                                                <option value="{{ $province->id }}">

                                                                    {{ $province->name }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>



                                                    {{-- CITY --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            เมือง / อำเภอ

                                                        </label>

                                                        <select class="form-select"
                                                            name="city_id"
                                                            id="city_id">

                                                            <option value="">

                                                                เลือกเมือง

                                                            </option>

                                                        </select>

                                                    </div>



                                                    {{-- ADDRESS --}}
                                                    <div class="col-md-12 mb-5">

                                                        <label class="form-label">

                                                            ที่อยู่

                                                        </label>

                                                        <textarea class="form-control"
                                                            rows="4"
                                                            name="address"
                                                            placeholder="ที่อยู่บริษัท"></textarea>

                                                    </div>



                                                    {{-- LOGO --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            โลโก้บริษัท

                                                        </label>

                                                        <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">

                                                            jpg, jpeg, png, webp

                                                        </span>

                                                        <input class="form-control"
                                                            type="file"
                                                            name="logo"
                                                            accept="image/jpg, image/jpeg, image/png, image/webp">

                                                    </div>



                                                    {{-- COVER --}}
                                                    <div class="col-md-6 mb-5">

                                                        <label class="form-label">

                                                            รูปปกบริษัท

                                                        </label>

                                                        <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">

                                                            jpg, jpeg, png, webp

                                                        </span>

                                                        <input class="form-control"
                                                            type="file"
                                                            name="cover_image"
                                                            accept="image/jpg, image/jpeg, image/png, image/webp">

                                                    </div>



                                                    {{-- DESCRIPTION --}}
                                                    <div class="col-md-12 mb-5">

                                                        <label class="form-label">

                                                            รายละเอียดบริษัท

                                                        </label>

                                                        <textarea class="form-control"
                                                            id="description"
                                                            rows="10"
                                                            name="description"></textarea>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- BUTTON -->
                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="d-flex justify-content-end mt-5">

                                                <a href="{{ url("$segment/$folder") }}"
                                                    class="btn btn-light me-2">

                                                    Cancel

                                                </a>

                                                <button type="submit"
                                                    class="btn btn-primary"
                                                    style="background: #1C2842;">

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

            <!-- SCROLL -->
            <div id="kt_scrolltop"
                class="scrolltop"
                data-kt-scrolltop="true">

                <i class="ki-duotone ki-arrow-up">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>

            </div>

        </div>

    </div>

    <!-- JS -->
    @include("$prefix.layout.script")

</body>



<script>

    /*
    |--------------------------------------------------------------------------
    | CKEDITOR
    |--------------------------------------------------------------------------
    */

    CKEDITOR.replace('description', {

        fullPage: true,

        allowedContent: true,

        height: 320,

        versionCheck: false,

        extraPlugins: 'uploadimage',

        filebrowserBrowseUrl:
            '/apps/ckfinder/3.4.5/ckfinder.html',

        filebrowserImageBrowseUrl:
            '/apps/ckfinder/3.4.5/ckfinder.html?type=Images',

        filebrowserUploadUrl:
            '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Files',

        filebrowserImageUploadUrl:
            '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Images',

        removeButtons: 'PasteFromWord'

    });



    /*
    |--------------------------------------------------------------------------
    | LOAD CITY
    |--------------------------------------------------------------------------
    */

    $('#province_id').change(function () {

        let id = $(this).val();

        $('#city_id').html(
            '<option value="">Loading...</option>'
        );

        $.ajax({

            url: '{{ url("api/location/city") }}/' + id,

            type: 'GET',

            success: function (res) {

                let html =
                    '<option value="">เลือกเมือง</option>';

                $.each(res, function (key, val) {

                    html += `
                        <option value="${val.id}">
                            ${val.name}
                        </option>
                    `;

                });

                $('#city_id').html(html);

            }

        });

    });

</script>

</html>