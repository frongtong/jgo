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

    @php
        $workExperiences = json_decode($data->work_experience, true);
    @endphp

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <div id="kt_app_header" class="app-header">
                @include("$prefix.layout.head-menu")
            </div>

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include("$prefix.layout.side-menu")

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                                @include("$prefix.layout.breadcrumbs")
                            </div>
                        </div>

                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                <form method="POST" action="{{ url("$segment/$folder/edit/$data->id") }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card card-flush py-4 mb-5">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>ข้อมูลผู้สมัคร</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row mb-5">
                                                        <div class="col-md-6">
                                                            <label class="form-label">ชื่อ</label>
                                                            <input type="text" class="form-control" value="{{ $data->first_name }}" readonly>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">นามสกุล</label>
                                                            <input type="text" class="form-control" value="{{ $data->last_name }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-4">
                                                            <label class="form-label">เบอร์โทร</label>
                                                            <input type="text" class="form-control" value="{{ $data->phone }}" readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">อีเมล</label>
                                                            <input type="text" class="form-control" value="{{ $data->email }}" readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">Line ID</label>
                                                            <input type="text" class="form-control" value="{{ $data->line_id ?: '-' }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-4">
                                                            <label class="form-label">อายุ</label>
                                                            <input type="text" class="form-control" value="{{ $data->age ?: '-' }}" readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">เพศ</label>
                                                            <input type="text" class="form-control" value="{{ $data->gender ?: '-' }}" readonly>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label">ระดับภาษาญี่ปุ่น</label>
                                                            <input type="text" class="form-control" value="{{ $data->japanese_level ?: '-' }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-12">
                                                            <label class="form-label">ที่อยู่</label>
                                                            <textarea class="form-control" rows="4" readonly>{{ $data->address }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-12">
                                                            <label class="form-label">การศึกษา</label>
                                                            <input type="text" class="form-control" value="{{ $data->education ?: '-' }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-5">
                                                        <div class="col-md-12">
                                                            <label class="form-label">ประสบการณ์ทำงาน</label>

                                                            @if(is_array($workExperiences) && count($workExperiences))
                                                                <div class="table-responsive">
                                                                    <table class="table table-row-dashed align-middle">
                                                                        <thead>
                                                                            <tr class="fw-bold text-gray-600">
                                                                                <th>บริษัท</th>
                                                                                <th>ตำแหน่ง</th>
                                                                                <th>รายละเอียดงาน</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($workExperiences as $work)
                                                                                <tr>
                                                                                    <td>{{ data_get($work, 'company_name', '-') }}</td>
                                                                                    <td>{{ data_get($work, 'position', '-') }}</td>
                                                                                    <td>{{ data_get($work, 'job_description', data_get($work, 'responsibilities', '-')) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <textarea class="form-control" rows="5" readonly>{{ $data->work_experience ?: '-' }}</textarea>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label">หมายเหตุผู้สมัคร</label>
                                                            <textarea class="form-control" rows="5" readonly>{{ $data->note }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card card-flush py-4 mb-5">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>ข้อมูลงาน</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="mb-5">
                                                        <label class="form-label">ตำแหน่งงาน</label>
                                                        <input type="text" class="form-control" value="{{ $data->job->title_th ?? '-' }}" readonly>
                                                    </div>

                                                    <div class="mb-5">
                                                        <label class="form-label">บริษัท</label>
                                                        <input type="text" class="form-control" value="{{ $data->job->company->name_th ?? '-' }}" readonly>
                                                    </div>

                                                    <div class="mb-5">
                                                        <label class="form-label">วันที่สมัคร</label>
                                                        <input type="text" class="form-control" value="{{ $data->created_at ? $data->created_at->format('d/m/Y H:i') : '-' }}" readonly>
                                                    </div>

                                                    <div class="d-grid gap-3">
                                                        @if($data->member_id)
                                                            <a href="{{ url("$segment/member/view/$data->member_id") }}" class="btn btn-light-info">
                                                                ดูข้อมูลสมาชิก
                                                            </a>
                                                        @endif

                                                        @if($data->resume_file)
                                                            <a href="{{ asset($data->resume_file) }}" target="_blank" class="btn btn-light-primary">
                                                                เปิดไฟล์ Resume
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card card-flush py-4">
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h2>จัดการสถานะ</h2>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="mb-5">
                                                        <label class="form-label">สถานะ</label>
                                                        <select name="status" class="form-select">
                                                            @foreach($statuses as $value => $label)
                                                                <option value="{{ $value }}" {{ $data->status === $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-5">
                                                        <label class="form-label">หมายเหตุ</label>
                                                        <textarea name="remark" rows="4" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-5">
                                        <a href="{{ url("$segment/$folder") }}" class="btn btn-light me-2">ยกเลิก</a>
                                        <button type="submit" class="btn btn-primary">บันทึก</button>
                                    </div>
                                </form>

                                <div class="card card-flush mt-10">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>ประวัติการเปลี่ยนสถานะ</h2>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed">
                                                <thead>
                                                    <tr>
                                                        <th>วันที่</th>
                                                        <th>จาก</th>
                                                        <th>เป็น</th>
                                                        <th>หมายเหตุ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($logs as $log)
                                                        <tr>
                                                            <td>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-' }}</td>
                                                            <td>{{ $statuses[$log->old_status] ?? ($log->old_status ?: '-') }}</td>
                                                            <td>{{ $statuses[$log->new_status] ?? ($log->new_status ?: '-') }}</td>
                                                            <td>{{ $log->remark ?: '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center">ยังไม่มีประวัติ</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="kt_app_footer" class="app-footer">
                        @include("$prefix.layout.footer")
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("$prefix.layout.script")
</body>

</html>
