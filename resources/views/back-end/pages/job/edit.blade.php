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

                                @csrf
                             

                                <div class="app-container container-xxl">

                                    <div class="card">

                                        <div class="card-body">

                                            <h3 class="mb-5">
                                                แก้ไขงาน
                                            </h3>

                                            {{-- บริษัท --}}
                                            <div class="row mb-5">

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        บริษัท
                                                    </label>

                                                    <select name="company_id"
                                                        class="form-select form-select-solid"
                                                        required>

                                                        <option value="">
                                                            เลือกบริษัท
                                                        </option>

                                                        @foreach($companies as $company)

                                                        <option value="{{ $company->id }}"
                                                            {{ $data->company_id == $company->id ? 'selected' : '' }}>

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

                                                        <option value="Full Time"
                                                            {{ $data->job_type=='Full Time'?'selected':'' }}>
                                                            Full Time
                                                        </option>

                                                        <option value="Part Time"
                                                            {{ $data->job_type=='Part Time'?'selected':'' }}>
                                                            Part Time
                                                        </option>

                                                        <option value="Contract"
                                                            {{ $data->job_type=='Contract'?'selected':'' }}>
                                                            Contract
                                                        </option>

                                                        <option value="Freelance"
                                                            {{ $data->job_type=='Freelance'?'selected':'' }}>
                                                            Freelance
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>

                                            {{-- ชื่องาน --}}
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        ชื่อตำแหน่งงาน
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        name="title_th"
                                                        value="{{ $data->title_th }}"
                                                        required>

                                                </div>

                                            </div>

                                            {{-- หมวดหมู่ --}}
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        หมวดหมู่งาน
                                                    </label>

                                                    @foreach($category1 as $cat1)

                                                    <div class="card border mb-3">

                                                        <div class="card-header  p-5">

                                                            <strong>
                                                                {{ $cat1->name_th }}
                                                            </strong>

                                                        </div>

                                                        <div class="card-body">

                                                            <div class="row">

                                                                @foreach($cat1->category2 as $cat2)

                                                                <div class="col-md-3 mb-2">

                                                                    <label class="form-check">

                                                                        <input type="checkbox"
                                                                            class="form-check-input"
                                                                            name="category2_id[]"
                                                                            value="{{ $cat2->id }}"
                                                                            {{ in_array($cat2->id,$jobCategories)?'checked':'' }}>

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

                                            {{-- เงินเดือน --}}
                                            <div class="row mb-5">

                                                <div class="col-md-3">

                                                    <label class="form-label">
                                                        เงินเดือนเริ่มต้น
                                                    </label>

                                                    <input type="number"
                                                        class="form-control"
                                                        name="salary_min"
                                                        value="{{ $data->salary_min }}">

                                                </div>

                                                <div class="col-md-3">

                                                    <label class="form-label">
                                                        เงินเดือนสูงสุด
                                                    </label>

                                                    <input type="number"
                                                        class="form-control"
                                                        name="salary_max"
                                                        value="{{ $data->salary_max }}">

                                                </div>

                                                <div class="col-md-3">

                                                    <label class="form-label">
                                                        สกุลเงิน
                                                    </label>

                                                    <input type="hidden"
                                                        name="currency"
                                                        value="JPY">

                                                    <input type="text"
                                                        class="form-control"
                                                        value="JPY"
                                                        readonly>

                                                </div>

                                                <div class="col-md-3">

                                                    <label class="form-label">
                                                        จำนวนรับ
                                                    </label>

                                                    <input type="number"
                                                        class="form-control"
                                                        name="qty"
                                                        value="{{ $data->qty }}">

                                                </div>

                                            </div>

                                            {{-- จังหวัด / เมือง --}}
                                            <div class="row mb-5">

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        จังหวัด
                                                    </label>

                                                    <select name="province_id"
                                                        id="province_id"
                                                        class="form-select">

                                                        @foreach($provinces as $province)

                                                        <option value="{{ $province->id }}"
                                                            {{ $data->province_id == $province->id ? 'selected' : '' }}>

                                                            {{ $province->name }}

                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>
                                                 <div class="col-md-6">

                                                        <label class="form-label">

                                                            วันที่

                                                        </label>

                                                        <input type="date"
                                                            class="form-control"
                                                            name="date"  value="{{ $data->date }}">

                                                    </div>

                                               

                                            </div>

                                            {{-- รูปภาพ --}}
                                            <div class="row mb-5">

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Logo
                                                    </label>

                                                    @if($data->logo)

                                                    <div class="mb-3">

                                                        <img src="{{ asset($data->logo) }}"
                                                            style="height:120px;">

                                                    </div>

                                                    @endif

                                                    <input type="file"
                                                        class="form-control"
                                                        name="logo">

                                                </div>

                                                <div class="col-md-6">

                                                    <label class="form-label">
                                                        Banner
                                                    </label>

                                                    @if($data->banner_image)

                                                    <div class="mb-3">

                                                        <img src="{{ asset($data->banner_image) }}"
                                                            style="max-width:300px;">

                                                    </div>

                                                    @endif

                                                    <input type="file"
                                                        class="form-control"
                                                        name="banner_image">

                                                </div>

                                            </div>

                                            {{-- รายละเอียด --}}
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        รายละเอียดงาน
                                                    </label>

                                                    <textarea name="detail"
                                                        id="detail"
                                                        rows="10"
                                                        class="form-control">{!! $data->detail !!}</textarea>

                                                </div>

                                            </div>

                                            {{-- สวัสดิการ --}}
                                            <div class="row mb-5">

                                                <div class="col-md-12">

                                                    <label class="form-label">
                                                        สวัสดิการ
                                                    </label>

                                                    <textarea name="welfare"
                                                        id="welfare"
                                                        rows="10"
                                                        class="form-control">{!! $data->welfare !!}</textarea>

                                                </div>

                                            </div>

                                            {{-- สถานะ --}}
                                            <div class="row mb-5">

                                                <div class="col-md-4">

                                                    <label class="form-label">
                                                        สถานะ
                                                    </label>

                                                    <select name="status"
                                                        class="form-select">

                                                        <option value="on"
                                                            {{ $data->status=='on'?'selected':'' }}>
                                                            เปิดรับสมัคร
                                                        </option>

                                                        <option value="off"
                                                            {{ $data->status=='off'?'selected':'' }}>
                                                            ปิดรับสมัคร
                                                        </option>

                                                    </select>

                                                </div>

                                            </div>

                                            <div class="text-end">

                                                <a href="{{ url("$segment/$folder") }}"
                                                    class="btn btn-light">

                                                    Cancel

                                                </a>

                                                <button type="submit"
                                                    class="btn btn-primary">

                                                    Save Changes

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
    CKEDITOR.replace('detail', {
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
    CKEDITOR.replace('welfare', {
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
