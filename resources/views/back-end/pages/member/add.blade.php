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

        <form action="{{ url('api/member/login') }}"
            id="form_submit"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="row">

                <!--begin::Left-->
                <div class="col-md-8">

                    <!--begin::Card-->
                    <div class="card card-flush py-4 mb-5">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>ข้อมูลสมาชิก</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <!-- Profile Image -->
                            <div class="mb-10">

                                <label class="form-label required">
                                    รูปโปรไฟล์
                                </label>

                                <span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">
                                    รองรับไฟล์ jpg, jpeg, png, webp
                                </span>

                                <input class="form-control mt-3"
                                    type="file"
                                    name="profile_image">

                            </div>

                            <!-- Username -->
                            <div class="row mb-5">

                                <div class="col-md-6">

                                    <label class="form-label required">
                                        Username
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="username">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label required">
                                        Password
                                    </label>

                                    <input type="password"
                                        class="form-control"
                                        name="password">

                                </div>

                            </div>

                            <!-- Email -->
                            <div class="row mb-5">

                                <div class="col-md-6">

                                    <label class="form-label required">
                                        Email
                                    </label>

                                    <input type="email"
                                        class="form-control"
                                        name="email">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        เบอร์โทรศัพท์
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="phone">

                                </div>

                            </div>

                            <hr class="my-10">

                            <!-- Thai Name -->
                            <div class="row mb-5">

                                <div class="col-md-2">

                                    <label class="form-label">
                                        คำนำหน้า
                                    </label>

                                    <select class="form-select"
                                        name="title_th">

                                        <option value="">Select</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>

                                    </select>

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label required">
                                        ชื่อ (TH)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="first_name_th">

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label required">
                                        นามสกุล (TH)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="last_name_th">

                                </div>

                            </div>

                            <!-- English Name -->
                            <div class="row mb-5">

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Title
                                    </label>

                                    <select class="form-select"
                                        name="title_en">

                                        <option value="">Select</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Mrs.">Mrs.</option>
                                        <option value="Miss">Miss</option>

                                    </select>

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        First Name (EN)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="first_name_en">

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Last Name (EN)
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="last_name_en">

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
                                        name="citizen_id">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        วันเกิด
                                    </label>

                                    <input type="date"
                                        class="form-control"
                                        name="birth_date">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        เพศ
                                    </label>

                                    <select class="form-select"
                                        name="gender">

                                        <option value="">Select</option>
                                        <option value="ชาย">ชาย</option>
                                        <option value="หญิง">หญิง</option>
                                        <option value="อื่นๆ">อื่นๆ</option>

                                    </select>

                                </div>

                            </div>

                            <!-- Contact -->
                            <div class="row mb-5">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Line ID
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="line_id">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Facebook
                                    </label>

                                    <input type="text"
                                        class="form-control"
                                        name="facebook">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        สถานะสมรส
                                    </label>

                                    <select class="form-select"
                                        name="marital_status">

                                        <option value="">Select</option>
                                        <option value="โสด">โสด</option>
                                        <option value="สมรส">สมรส</option>
                                        <option value="หย่า">หย่า</option>

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
                                    name="current_address"></textarea>

                            </div>

                        </div>

                    </div>
                    <!--end::Card-->

                </div>
                <!--end::Left-->

                <!--begin::Right-->
                <div class="col-md-4">

                    <!--begin::Status-->
                    <div class="card card-flush py-4 mb-5">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>Status</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <select class="form-select"
                                name="status">

                               

                                <option value="pending" selected>
                                    Pending
                                </option>

                                <option value="active">
                                    Approved
                                </option>

                                <option value="inactive">
                                    inactive
                                </option>

                            </select>

                        </div>

                    </div>
                    <!--end::Status-->

                    <!--begin::Information-->
                    <div class="card card-flush py-4">

                        <div class="card-header">
                            <div class="card-title">
                                <h2>ข้อมูลระบบ</h2>
                            </div>
                        </div>

                        <div class="card-body pt-0">

                            <div class="mb-5">

                                <label class="form-label">
                                    วันที่สมัคร
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="{{ date('d/m/Y H:i') }}"
                                    disabled>

                            </div>

                            <div class="mb-5">

                                <label class="form-label">
                                    Member Code
                                </label>

                                <input type="text"
                                    class="form-control"
                                    value="Auto Generate"
                                    disabled>

                            </div>

                        </div>

                    </div>
                    <!--end::Information-->

                </div>
                <!--end::Right-->

            </div>

            <!--begin::Action-->
            <div class="d-flex justify-content-end mt-10">

                <a href="{{ url("$segment/$folder") }}"
                    class="btn btn-light me-3">

                    Cancel

                </a>

                <button type="submit"
                    class="btn btn-primary">

                    <span class="indicator-label">
                        Save Changes
                    </span>

                </button>

            </div>
            <!--end::Action-->

        </form>

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
        let formCountRefer = 0;
        const formContainerRefer = document.getElementById("form-container-Refer");

        // Initialize the visibility of the "ชื่อไฟล์" input based on the initial form's selection
        document.querySelectorAll('.form-select').forEach((select, index) => {
            toggleNameFileInput(index);
        });

        document.getElementById("add-form-refer-btn").addEventListener("click", function() {
            formCountRefer++;
            const divRefer = document.createElement("div");
            divRefer.setAttribute("id", `refer${formCountRefer}`);
            divRefer.innerHTML = `  <div class="card mb-4">
                                                        <div class="card-header bg-dark">
                                                            <h5 class="mt-5 text-white">ส่วนlogoที่ ${formCountRefer+1}</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="intro-y col-span-12 sm:col-span-6">
                                                                    <label class="form-label">
                                                                        <span
                                                                            class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">ภาษาไทย</span>
                                                                    </label>
                                                                    <div class="input-group mb-5">
                                                                        <span class="input-group-text">หัวข้อ</span>
                                                                        <input class="form-control" type="text"
                                                                            name="head_logo_th[]">
                                                                    </div>
                                                                    <div class="input-group mb-5">
                                                                        <span
                                                                            class="input-group-text">รายละเอียด</span>
                                                                        <input class="form-control" type="text"
                                                                            name="detail_logo_th[]">
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
                                                                            name="head_logo_en[]">
                                                                    </div>
                                                                    <div class="input-group mb-5">
                                                                        <span
                                                                            class="input-group-text">รายละเอียด</span>
                                                                        <input class="form-control" type="text"
                                                                            name="detail_logo_en[]">
                                                                    </div>
                                                                     <label class="form-label">รูปภาพLOGO  <span
                                                                            class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">ขนาดรูปแนะนำ110x110</span></label>
                                                                    <div class="input-group mb-5">
                                                                        <input class="form-control" type="file"
                                                                            name="logo[]">
                                                                    </div>
                                                                    <div id="form-container-Refer"></div>
                                                                </div>
                                                            </div>
                                                    <button type="button" class="btn btn-danger shadow-md ml-2 mt-3 mb-3" onclick="del_Refer(${formCountRefer})">-</button>
                                                </div>
                                            </div>    `;
            formContainerRefer.appendChild(divRefer);
        });

        function del_Refer(index) {
            const divRefer = document.getElementById(`refer${index}`);
            if (divRefer) {
                if (confirm(`Are you sure you want to delete item ${index}?`)) {
                    formContainerRefer.removeChild(divRefer);
                    formCountRefer--;
                }
            }
        }
    </script>
   
    <script>
       
    </script>
</body>

</html>
