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

                @include("$prefix.layout.side-menu")

                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                    <div id="kt_app_toolbar_container"
                        class="app-container container-xxl d-flex flex-stack">

                        @include("$prefix.layout.breadcrumbs")

                    </div>

                </div>

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                    <div class="d-flex flex-column flex-column-fluid">

                        <div id="kt_app_content"
                            class="app-content flex-column-fluid">

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
                                                <h3>เพิ่มงาน</h3>
                                            </div>

                                            <div class="container-fluid">

                                                <!-- COMPANY -->
                                                <div class="row mb-5">

                                                    <div class="col-md-6">

                                                        <label class="form-label">

                                                            บริษัท
                                                            <span class="text-danger">*</span>

                                                        </label>

                                                        <select name="company_id"
                                                            class="form-select form-select-solid"
                                                            required>

                                                            <option value="">

                                                                เลือกบริษัท

                                                            </option>

                                                            @foreach($companies as $company)

                                                                <option value="{{ $company->id }}">

                                                                    {{ $company->name_th }}

                                                                </option>

                                                            @endforeach

                                                        </select>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <label class="form-label">

                                                            ประเภทงาน

                                                        </label>

                                                        <select name="job_type"
                                                            class="form-select form-select-solid">

                                                            <option value="Full Time">

                                                                Full Time

                                                            </option>

                                                            <option value="Part Time">

                                                                Part Time

                                                            </option>

                                                            <option value="Contract">

                                                                Contract

                                                            </option>

                                                            <option value="Freelance">

                                                                Freelance

                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>



                                                <!-- TITLE -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            ชื่อตำแหน่งงาน 

                                                            <span class="text-danger">*</span>

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="title_th"
                                                            required>

                                                    </div>

                                                   

                                                </div>



                                                <!-- CATEGORY -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            หมวดหมู่งาน

                                                        </label>

                                                        @foreach($category1 as $cat1)

                                                            <div class="card border mb-3 ">

                                                                <div class="card-header bg-light p-3" >

                                                                    <b>

                                                                        {{ $cat1->name_th }}

                                                                    </b>

                                                                </div>

                                                                <div class="card-body">

                                                                    <div class="row">

                                                                        @foreach($cat1->category2 as $cat2)

                                                                            <div class="col-md-3 mb-2">

                                                                                <label class="form-check form-check-custom form-check-solid">

                                                                                    <input class="form-check-input"
                                                                                        type="checkbox"
                                                                                        name="category2_id[]"
                                                                                        value="{{ $cat2->id }}">

                                                                                    <span class="form-check-label">

                                                                                        {{ $cat2->name_th }}

                                                                                    </span>

                                                                                </label>

                                                                            </div>

                                                                        @endforeach

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        @endforeach

                                                    </div>

                                                </div>



                                                <!-- SALARY -->
                                                <div class="row mb-5">

                                                    <div class="col-md-3">

                                                        <label class="form-label">

                                                            เงินเดือนเริ่มต้น

                                                        </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="salary_min">

                                                    </div>

                                                    <div class="col-md-3">

                                                        <label class="form-label">

                                                            เงินเดือนสูงสุด

                                                        </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="salary_max">

                                                    </div>

                                                    <div class="col-md-3">

                                                        <label class="form-label">

                                                            สกุลเงิน

                                                        </label>

                                                        <select name="currency"
                                                            class="form-select form-select-solid">

                                                            <option value="">

                                                                เลือก

                                                            </option>

                                                            <option value="JPY">

                                                                JPY

                                                            </option>

                                                           

                                                        </select>

                                                    </div>

                                                    <div class="col-md-3">

                                                        <label class="form-label">

                                                            จำนวนรับ

                                                        </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="qty">

                                                    </div>

                                                </div>



                                                <!-- LOCATION -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            จังหวัด

                                                        </label>

                                                        <select name="province_id"
                                                            id="province_id"
                                                            class="form-select form-select-solid">

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


                                                </div>



                                                <!-- GENDER -->
                                                <div class="row mb-5">

                                                    <div class="col-md-4">

                                                        <label class="form-label">

                                                            เพศ

                                                        </label>

                                                        <select name="gender"
                                                            class="form-select form-select-solid">

                                                            <option value="all">

                                                                ทุกเพศ

                                                            </option>

                                                            <option value="male">

                                                                ชาย

                                                            </option>

                                                            <option value="female">

                                                                หญิง

                                                            </option>

                                                        </select>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <label class="form-label">

                                                            อายุขั้นต่ำ

                                                        </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="age_min">

                                                    </div>

                                                    <div class="col-md-4">

                                                        <label class="form-label">

                                                            อายุสูงสุด

                                                        </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="age_max">

                                                    </div>

                                                </div>



                                                <!-- IMAGE -->
                                                <div class="row mb-5">

                                                    <div class="col-md-6">

                                                        <label class="form-label">

                                                            โลโก้งาน

                                                        </label>

                                                        <input type="file"
                                                            class="form-control"
                                                            name="logo">

                                                    </div>

                                                    <div class="col-md-6">

                                                        <label class="form-label">

                                                            รูป Banner

                                                        </label>

                                                        <input type="file"
                                                            class="form-control"
                                                            name="banner_image">

                                                    </div>

                                                </div>



                                                <!-- DETAIL -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            รายละเอียดงาน

                                                        </label>

                                                        <textarea name="detail"
                                                            id="detail"
                                                            rows="10"
                                                            class="form-control"></textarea>

                                                    </div>

                                                </div>



                                                <!-- WELFARE -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            สวัสดิการ

                                                        </label>

                                                        <textarea name="welfare"
                                                            id="welfare"
                                                            rows="8"
                                                            class="form-control"></textarea>

                                                    </div>

                                                </div>



                                                <!-- MAP -->
                                                <div class="row mb-5">

                                                    <div class="col-md-12">

                                                        <label class="form-label">

                                                            Google Map Link

                                                        </label>

                                                        <input type="text"
                                                            class="form-control"
                                                            name="map_link">

                                                    </div>

                                                </div>



                                                <!-- STATUS -->
                                                <div class="row mb-5">

                                                    <div class="col-md-4">

                                                        <label class="form-label">

                                                            Status

                                                        </label>

                                                        <select name="status"
                                                            class="form-select form-select-solid">

                                                            <option value="on">

                                                                เปิดใช้งาน

                                                            </option>

                                                            <option value="off">

                                                                ปิดใช้งาน

                                                            </option>

                                                        </select>

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

    <!--begin::Javascript-->
    @include("$prefix.layout.script")
    <!--end::Javascript-->

</body>

<script>

    /*
    |--------------------------------------------------------------------------
    | CKEDITOR
    |--------------------------------------------------------------------------
    */

    CKEDITOR.replace('detail', {

        fullPage: true,
        allowedContent: true,
        height: 320,
        versionCheck: false,

    });

    CKEDITOR.replace('welfare', {

        fullPage: true,
        allowedContent: true,
        height: 250,
        versionCheck: false,

    });



    /*
    |--------------------------------------------------------------------------
    | LOAD CITY
    |--------------------------------------------------------------------------
    */

   

</script>

</html>