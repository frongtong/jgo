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
                                <div class="card card-flush">
                                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                        <div class="card-title">
                                            <h3>รายการใบสมัครงาน</h3>
                                        </div>

                                        <div class="card-toolbar">
                                            <a href="{{ url("$segment/$folder/export") . '?' . http_build_query(request()->query()) }}"
                                                class="btn btn-light-success">
                                                Export Excel
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <form method="GET" class="mb-8">
                                            <div class="row g-5">
                                                <div class="col-md-3">
                                                    <label class="form-label">ค้นหา</label>
                                                    <input type="text"
                                                        name="search"
                                                        value="{{ request('search') }}"
                                                        class="form-control form-control-solid"
                                                        placeholder="ชื่อ / อีเมล / เบอร์โทร">
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">ตำแหน่งงาน</label>
                                                    <select name="job_id" class="form-select form-select-solid">
                                                        <option value="">ทั้งหมด</option>
                                                        @foreach($jobs as $job)
                                                            <option value="{{ $job->id }}" {{ (string) request('job_id') === (string) $job->id ? 'selected' : '' }}>
                                                                {{ $job->title_th }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">สถานะ</label>
                                                    <select name="status" class="form-select form-select-solid">
                                                        <option value="">ทั้งหมด</option>
                                                        @foreach($statuses as $value => $label)
                                                            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">สมัครตั้งแต่</label>
                                                    <input type="date"
                                                        name="date_from"
                                                        value="{{ request('date_from') }}"
                                                        class="form-control form-control-solid">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">ถึงวันที่</label>
                                                    <input type="date"
                                                        name="date_to"
                                                        value="{{ request('date_to') }}"
                                                        class="form-control form-control-solid">
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 mt-5">
                                                <a href="{{ url("$segment/$folder") }}" class="btn btn-light">ล้างค่า</a>
                                                <button class="btn btn-success">ค้นหา</button>
                                            </div>
                                        </form>

                                        <div class="mb-5 text-muted">
                                            พบ {{ number_format($items->total()) }} รายการ
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-row-dashed align-middle">
                                                <thead>
                                                    <tr class="fw-bold text-gray-600">
                                                        <th width="5%">#</th>
                                                        <th>ผู้สมัคร</th>
                                                        <th>งานที่สมัคร</th>
                                                        <th>เบอร์โทร</th>
                                                        <th>อีเมล</th>
                                                        <th>สถานะ</th>
                                                        <th>วันที่สมัคร</th>
                                                        <th>นัดสัมภาษณ์</th>
                                                        <th width="12%" class="text-center">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($items as $key => $item)
                                                        <tr>
                                                            <td>{{ $items->firstItem() + $key }}</td>
                                                            <td>
                                                                <div class="fw-bold">
                                                                    {{ $item->first_name }} {{ $item->last_name }}
                                                                </div>

                                                                @if($item->member_id)
                                                                    <a href="{{ url("$segment/member/view/$item->member_id") }}"
                                                                        class="text-muted fs-7">
                                                                        ดูข้อมูลสมาชิก
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="fw-bold">{{ $item->job->title_th ?? '-' }}</div>
                                                                <div class="text-muted fs-7">{{ $item->job->company->name_th ?? '-' }}</div>
                                                            </td>
                                                            <td>{{ $item->phone ?: '-' }}</td>
                                                            <td>{{ $item->email ?: '-' }}</td>
                                                            <td>
                                                                @php
                                                                    $badge = match ($item->status) {
                                                                        'new' => 'badge-warning',
                                                                        'reviewing' => 'badge-light-primary',
                                                                        'interview' => 'badge-info',
                                                                        'passed' => 'badge-success',
                                                                        'failed' => 'badge-danger',
                                                                        default => 'badge-light',
                                                                    };
                                                                @endphp
                                                                <span class="badge {{ $badge }}">
                                                                    {{ $statuses[$item->status] ?? $item->status }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                                                            <td>
                                                                <div>{{ $item->interview_date ? $item->interview_date->format('d/m/Y') : '-' }}</div>
                                                                <div class="text-muted fs-7">{{ $item->interview_location ?: '' }}</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ url("$segment/$folder/edit/$item->id") }}"
                                                                    class="btn btn-icon btn-light-info btn-sm">
                                                                    <i class="ki-duotone ki-eye fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                    </i>
                                                                </a>

                                                                <button type="button"
                                                                    onclick="deleteItem({{ $item->id }})"
                                                                    class="btn btn-icon btn-light-danger btn-sm">
                                                                    <i class="ki-duotone ki-trash fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                    </i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center">ไม่พบข้อมูล</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-5">
                                            {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}
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

    <script>
        var fullUrl = window.location.origin + window.location.pathname;

        function deleteItem(id) {
            Swal.fire({
                title: 'ลบใบสมัคร',
                text: 'คุณต้องการลบใบสมัครนี้ใช่หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(fullUrl + '/destroy', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('ลบแล้ว', 'ลบใบสมัครเรียบร้อยแล้ว', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('ไม่สำเร็จ', 'ไม่สามารถลบใบสมัครได้', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
                }
            });
        }
    </script>
</body>

</html>
