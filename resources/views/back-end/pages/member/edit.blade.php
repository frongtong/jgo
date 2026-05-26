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

    <div id="kt_app_content_container" class="app-container container-xxl">

        <form action="{{ url("webpanel/member/edit/$row->id") }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="row">

                <!-- Left -->
                <div class="col-md-8">

                    <div class="card card-flush py-4 mb-5">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>Edit Member</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <!-- Image -->
                            <div class="mb-10">

                                <label class="form-label">
                                    รูปโปรไฟล์
                                </label>

                                @if(@$row->profile->profile_image)

                                    <div class="mb-5">

                                        <img src="{{ asset($row->profile->profile_image) }}"
                                            class="w-150px rounded">

                                    </div>

                                @endif

                                <input type="file"
                                    class="form-control"
                                    name="profile_image">

                            </div>

                            <!-- Username / Password -->
                            <div class="row mb-5">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Username
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="username"
                                        value="{{ $row->username }}">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Password
                                    </label>

                                    <input type="password"
                                        class="form-control"
                                        name="password">

                                    <small class="text-muted">
                                        เว้นว่างหากไม่เปลี่ยนรหัสผ่าน
                                    </small>

                                </div>

                            </div>

                            <!-- Email / Phone -->
                            <div class="row mb-5">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{ $row->email }}">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        เบอร์โทรศัพท์
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="phone"
                                        value="{{ @$row->profile->phone }}">

                                </div>

                            </div>

                            <hr class="my-10">

                            <!-- TH Name -->
                            <div class="row mb-5">

                                <div class="col-md-2">

                                    <label class="form-label">
                                        คำนำหน้า
                                    </label>

                                    <select class="form-select"
                                        name="title_th">

                                        <option value="">Select</option>

                                        <option value="นาย"
                                            {{ @$row->profile->title_th == 'นาย' ? 'selected' : '' }}>
                                            นาย
                                        </option>

                                        <option value="นาง"
                                            {{ @$row->profile->title_th == 'นาง' ? 'selected' : '' }}>
                                            นาง
                                        </option>

                                        <option value="นางสาว"
                                            {{ @$row->profile->title_th == 'นางสาว' ? 'selected' : '' }}>
                                            นางสาว
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        ชื่อ (TH)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="first_name_th"
                                        value="{{ @$row->profile->first_name_th }}">

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        นามสกุล (TH)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="last_name_th"
                                        value="{{ @$row->profile->last_name_th }}">

                                </div>

                            </div>

                            <!-- EN Name -->
                            <div class="row mb-5">

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Title
                                    </label>

                                    <select class="form-select"
                                        name="title_en">

                                        <option value="">Select</option>

                                        <option value="Mr."
                                            {{ @$row->profile->title_en == 'Mr.' ? 'selected' : '' }}>
                                            Mr.
                                        </option>

                                        <option value="Mrs."
                                            {{ @$row->profile->title_en == 'Mrs.' ? 'selected' : '' }}>
                                            Mrs.
                                        </option>

                                        <option value="Miss"
                                            {{ @$row->profile->title_en == 'Miss' ? 'selected' : '' }}>
                                            Miss
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        First Name (EN)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="first_name_en"
                                        value="{{ @$row->profile->first_name_en }}">

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Last Name (EN)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="last_name_en"
                                        value="{{ @$row->profile->last_name_en }}">

                                </div>

                            </div>

                            <!-- Personal -->
                            <div class="row mb-5">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        เลขบัตรประชาชน
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="citizen_id"
                                        value="{{ @$row->profile->citizen_id }}">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        วันเกิด
                                    </label>

                                    <input type="date"
                                        class="form-control"
                                        name="birth_date"
                                        value="{{ @$row->profile->birth_date }}">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        เพศ
                                    </label>

                                    <select class="form-select"
                                        name="gender">

                                        <option value="">Select</option>

                                        <option value="ชาย"
                                            {{ @$row->profile->gender == 'ชาย' ? 'selected' : '' }}>
                                            ชาย
                                        </option>

                                        <option value="หญิง"
                                            {{ @$row->profile->gender == 'หญิง' ? 'selected' : '' }}>
                                            หญิง
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <!-- Address -->
                            <div class="mb-5">

                                <label class="form-label">
                                    ที่อยู่ปัจจุบัน
                                </label>

                                <textarea class="form-control"
                                    rows="4"
                                    name="current_address">{{ @$row->profile->current_address }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Right -->
                <div class="col-md-4">

                    <!-- Status -->
                    <div class="card card-flush py-4 mb-5">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>Status</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <select class="form-select"
                                name="status">
                                <option value="pending"
                                    {{ $row->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="active"
                                    {{ $row->status == 'active' ? 'selected' : '' }}>
                                    Approved
                                </option>

                                <option value="inactive"
                                    {{ $row->status == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>
<!-- ===================== -->
<!-- Parent Information -->
<!-- ===================== -->

<div class="card card-flush py-4 mb-5">

    <div class="card-header">

        <div class="card-title">

            <h2>ข้อมูลผู้ปกครอง</h2>

        </div>

    </div>

    <div class="card-body pt-0">

        @if(@$row->parent)

            <!-- Username -->
            <div class="mb-5">

                <label class="form-label">
                    Username
                </label>

                <input type="text"
                    class="form-control"
                    value="{{ @$row->parent->username }}"
                    disabled>

            </div>

            <!-- Email -->
            <div class="mb-5">

                <label class="form-label">
                    Email
                </label>

                <input type="text"
                    class="form-control"
                    value="{{ @$row->parent->email }}"
                    disabled>

            </div>

            <!-- Password -->
            <div class="mb-5">

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">

                    <input type="text"
                        class="form-control"
                        id="parent_password"
                        value="{{ @$row->parent->parent_plain_password }}"
                        readonly>

                    <button type="button"
                        class="btn btn-light-primary"
                        onclick="copyParentPassword()">

                        Copy

                    </button>

                </div>

            </div>

            <!-- Status -->
            <div class="mb-5">

                <label class="form-label">
                    Status
                </label>

                <div>

                    @if(@$row->parent->status == 'approved')

                        <span class="badge badge-light-success">
                            Approved
                        </span>

                    @elseif(@$row->parent->status == 'pending')

                        <span class="badge badge-light-warning">
                            Pending
                        </span>

                    @else

                        <span class="badge badge-light-danger">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

            <!-- Created -->
            <div class="mb-5">

                <label class="form-label">
                    Created Date
                </label>

                <input type="text"
                    class="form-control"
                    value="{{ date('d/m/Y H:i', strtotime($row->parent->created_at)) }}"
                    disabled>

            </div>

        @else

            <div class="alert alert-warning">

                ไม่พบข้อมูลผู้ปกครอง

            </div>

        @endif

    </div>

</div>
                    <!-- Information -->
                     
                    <div class="card card-flush py-4">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>ข้อมูลระบบ</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <div class="mb-5">

                                <label class="form-label">
                                    Member Code
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ $row->member_code }}"
                                    disabled>

                            </div>

                            <div class="mb-5">

                                <label class="form-label">
                                    วันที่สมัคร
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ date('d/m/Y H:i', strtotime($row->apply_date)) }}"
                                    disabled>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Action -->
            <div class="d-flex justify-content-end mt-10">

                <a href="{{ url("$segment/$folder") }}"
                    class="btn btn-light me-3">

                    Cancel

                </a>

                <button type="submit"
                    class="btn btn-primary">

                    Update Changes

                </button>

            </div>

        </form>

    </div>

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
        </div>
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

function copyParentPassword()
{
    let copyText =
        document.getElementById("parent_password");

    copyText.select();

    copyText.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(
        copyText.value
    );

    Swal.fire({
        icon: 'success',
        title: 'Copied',
        text: 'Copy password success',
        timer: 1200,
        showConfirmButton: false
    });
}

</script>
    <!--end::Javascript-->

</body>
<!--end::Body-->

</html>
