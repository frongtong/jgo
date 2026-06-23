<!DOCTYPE html>
<html lang="th">
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
                                                            name="profile_image"
                                                            accept="image/jpeg,image/png,image/webp">

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

                                            @php

                                            $educationConfig = [

                                            'studying' => [
                                            'title' => 'ข้อมูลการศึกษา'
                                            ],
                                            'lower_secondary' => [
                                            'title' => 'มัธยมศึกษาตอนต้น'
                                            ],
                                            'vocational' => [
                                            'title' => 'มัธยมศึกษาตอนปลาย / ปวช.'
                                            ],

                                            'high_vocational' => [
                                            'title' => 'ปวส.'
                                            ],

                                            'bachelor' => [
                                            'title' => 'ปริญญาตรี'
                                            ],

                                            'other' => [
                                            'title' => 'กรณีไม่เรียนตามเกณฑ์/หยุดพักการเรียน/
                                            หยุดเรียนกลางคัน'
                                            ],




                                            ];

                                            $months = [
                                            1 => 'มกราคม',
                                            2 => 'กุมภาพันธ์',
                                            3 => 'มีนาคม',
                                            4 => 'เมษายน',
                                            5 => 'พฤษภาคม',
                                            6 => 'มิถุนายน',
                                            7 => 'กรกฎาคม',
                                            8 => 'สิงหาคม',
                                            9 => 'กันยายน',
                                            10 => 'ตุลาคม',
                                            11 => 'พฤศจิกายน',
                                            12 => 'ธันวาคม'
                                            ];
                                            $educationLevels = [
                                            'primary' => 'ประถมศึกษา',
                                            'lower_secondary' => 'มัธยมศึกษาตอนต้น (ม.3)',
                                           
                                            'vocational' => 'ปวช.',
                                            'high_vocational' => 'ปวส.',
                                            'bachelor' => 'ปริญญาตรี',
                                            'master' => 'ปริญญาโท',
                                            'doctorate' => 'ปริญญาเอก',
                                            'other' => 'อื่น ๆ',
                                            ];
                                            @endphp

                                            @foreach($educationConfig as $key => $config)

                                            @php
                                            $item = $educationData[$key] ?? null;
                                            @endphp


                                            @if($key == 'studying')
                                            <div class="card card-flush py-4 mb-5">

                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>ข้อมูลการศึกษา</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <input type="hidden"
                                                        name="{{ $key }}[id]"
                                                        value="{{ @$item->id }}">

                                                    <div class="row">

                                                        <!-- ชื่อสถาบัน -->
                                                        <div class="col-md-12 mb-5">

                                                            <label class="form-label">
                                                                ชื่อสถาบัน
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="{{ $key }}[institution_name]"
                                                                value="{{ @$item->institution_name }}">

                                                        </div>

                                                        <!-- สาขาวิชา -->
                                                        <div class="col-md-12 mb-5">

                                                            <label class="form-label">
                                                                สาขาวิชา
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="{{ $key }}[major]"
                                                                value="{{ @$item->major }}">

                                                        </div>

                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label required">
                                                                ระดับการศึกษา
                                                            </label>

                                                            <select
                                                                class="form-select"
                                                                name="{{ $key }}[education_level]">

                                                                <option value="">
                                                                    เลือกระดับการศึกษา
                                                                </option>

                                                                @foreach($educationLevels as $value => $label)

                                                                <option
                                                                    value="{{ $value }}"
                                                                    {{ @$item->education_level == $value ? 'selected' : '' }}>

                                                                    {{ $label }}

                                                                </option>

                                                                @endforeach

                                                            </select>

                                                        </div>
                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                อื่นๆ(โปรดระบุ)
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="{{ $key }}[note]"
                                                                value="{{ @$item->note }}">

                                                        </div>


                                                    </div>

                                                </div>

                                            </div>
                                            @elseif($key == 'other')
                                            <div class="card card-flush py-4 mb-5">

                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>{{ $config['title'] }}</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <input type="hidden"
                                                        name="{{ $key }}[id]"
                                                        value="{{ @$item->id }}">

                                                    <input type="hidden"
                                                        name="{{ $key }}[education_level]"
                                                        value="{{ $key }}">

                                                    <div class="row">

                                                        <!-- ชื่อสถาบัน -->
                                                        <div class="col-md-12 mb-5">

                                                            <textarea
                                                                class="form-control"
                                                                name="{{ $key }}[note]"
                                                                rows="4">{{ @$item->note }}</textarea>

                                                        </div>


                                                    </div>

                                                </div>

                                            </div>
                                            @else
                                            <div class="card card-flush py-4 mb-5">

                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>{{ $config['title'] }}</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <input type="hidden"
                                                        name="{{ $key }}[id]"
                                                        value="{{ @$item->id }}">

                                                    <input type="hidden"
                                                        name="{{ $key }}[education_level]"
                                                        value="{{ $key }}">

                                                    <div class="row">

                                                        <!-- ชื่อสถาบัน -->
                                                        <div class="col-md-12 mb-5">

                                                            <label class="form-label">
                                                                ชื่อสถาบัน
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="{{ $key }}[institution_name]"
                                                                value="{{ @$item->institution_name }}">

                                                        </div>

                                                        <!-- สาขาวิชา -->
                                                        <div class="col-md-12 mb-5">

                                                            <label class="form-label">
                                                                สาขาวิชา
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="{{ $key }}[major]"
                                                                value="{{ @$item->major }}">

                                                        </div>

                                                        <!-- เดือน -->
                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                เริ่มเรียน (เดือน)
                                                            </label>

                                                            <select
                                                                class="form-select"
                                                                name="{{ $key }}[start_month]">

                                                                <option value="">
                                                                    เลือกเดือน
                                                                </option>

                                                                @foreach($months as $monthNo => $monthName)

                                                                <option value="{{ $monthNo }}"
                                                                    {{ @$item->start_month == $monthNo ? 'selected' : '' }}>

                                                                    {{ $monthName }}

                                                                </option>

                                                                @endforeach

                                                            </select>

                                                        </div>

                                                        <!-- ปี -->
                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                เริ่มเรียน (พ.ศ.)
                                                            </label>

                                                            <select
                                                                class="form-select"
                                                                name="{{ $key }}[start_year]">

                                                                <option value="">
                                                                    เลือกปี
                                                                </option>

                                                                @for($year = date('Y') + 543; $year >= 2500; $year--)

                                                                <option value="{{ $year }}"
                                                                    {{ @$item->start_year == $year ? 'selected' : '' }}>

                                                                    {{ $year }}

                                                                </option>

                                                                @endfor

                                                            </select>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                            @endif
                                            @endforeach

                                            @php
                                            $training = $row->trainingCourses->first();
                                            @endphp

                                            <div class="card card-flush py-4 mb-5">

                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>ข้อมูลด้านการอบรม</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">

                                                    <input type="hidden"
                                                        name="training[training_id]"
                                                        value="{{ old('training.training_id', $training?->training_id) }}">

                                                    <div class="row">
                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                ประเภทหลักสูตร
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="training[program_type]"
                                                                value="{{ old('training.program_type', $training?->program_type) }}">

                                                        </div>

                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                ชื่อสถาบัน
                                                            </label>

                                                            <input type="text"
                                                                class="form-control"
                                                                name="training[institution_name]"
                                                                value="{{ old('training.institution_name', $training?->institution_name) }}">

                                                        </div>


                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                เริ่มอบรม
                                                            </label>

                                                            <input type="month"
                                                                class="form-control"
                                                                name="training[start_month_year]"
                                                                value="{{ old('training.start_month_year', $training?->start_month_year) }}">

                                                        </div>


                                                        <div class="col-md-6 mb-5">

                                                            <label class="form-label">
                                                                สิ้นสุดการอบรม
                                                            </label>

                                                            <input type="month"
                                                                class="form-control"
                                                                name="training[end_month_year]"
                                                                value="{{ old('training.end_month_year', $training?->end_month_year) }}">

                                                        </div>


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

                                                <div class="card-header align-items-center">

                                                    <div class="card-title">

                                                        <h2>ข้อมูลผู้ปกครอง ({{ $row->parents->count() }})</h2>

                                                    </div>

                                                    <div class="card-toolbar">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light-primary"
                                                            form="create-parent-form">
                                                            สร้างผู้ปกครองเพิ่ม
                                                        </button>
                                                    </div>

                                                </div>

                                                <div class="card-body pt-0">

                                                    @if(session('parent_credentials'))
                                                    <div class="alert alert-success">
                                                        <div class="fw-bold mb-2">
                                                            สร้างบัญชีสำเร็จ กรุณาบันทึกรหัสผ่านนี้ทันที
                                                        </div>
                                                        <div>Username: <code>{{ session('parent_credentials.username') }}</code></div>
                                                        <div>Email: <code>{{ session('parent_credentials.email') }}</code></div>
                                                        <div>Password: <code>{{ session('parent_credentials.password') }}</code></div>
                                                        <div class="small mt-2">
                                                            รหัสผ่านจะแสดงเฉพาะครั้งนี้เท่านั้น
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if(session('error'))
                                                    <div class="alert alert-danger">
                                                        {{ session('error') }}
                                                    </div>
                                                    @endif

                                                    @forelse($row->parents as $parent)
                                                    <div class="border rounded p-4 mb-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div>
                                                                <div class="fw-bold text-gray-800">
                                                                    {{ $parent->username }}
                                                                </div>
                                                                <div class="text-muted small">
                                                                    {{ $parent->member_code }}
                                                                </div>
                                                            </div>

                                                            @if(in_array($parent->status, ['approved', 'active']))
                                                            <span class="badge badge-light-success">Approved</span>
                                                            @elseif($parent->status === 'pending')
                                                            <span class="badge badge-light-warning">Pending</span>
                                                            @else
                                                            <span class="badge badge-light-danger">Inactive</span>
                                                            @endif
                                                        </div>

                                                        <div class="small mb-2">
                                                            <span class="text-muted">Email:</span>
                                                            {{ $parent->email }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            สร้างเมื่อ
                                                            {{ $parent->created_at ? $parent->created_at->format('d/m/Y H:i') : '-' }}
                                                        </div>
                                                    </div>
                                                    @empty
                                                    <div class="alert alert-warning mb-0">
                                                        ไม่พบข้อมูลผู้ปกครอง
                                                    </div>
                                                    @endforelse

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

                                <form id="create-parent-form"
                                    action="{{ url("webpanel/member/edit/$row->id/parents") }}"
                                    method="POST"
                                    class="d-none">
                                    @csrf
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
    <!--end::Javascript-->

</body>
<!--end::Body-->

</html>
