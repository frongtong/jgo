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
                            <div id="kt_app_toolbar_container"
                                class="app-container container-xxl d-flex flex-stack">

                                @include("$prefix.layout.breadcrumbs")

                            </div>
                        </div>

                        <div id="kt_app_content"
                            class="app-content flex-column-fluid">

                            <div id="kt_app_content_container"
                                class="app-container container-xxl">

                                <div class="card card-flush">

                                    <div class="card-header align-items-center py-5">

                                        <h3 class="card-title">
                                            รายการสมัครงาน
                                        </h3>

                                    </div>

                                    <div class="card-body">

                                        <form method="GET">

                                            <div class="row mb-5">

                                                <div class="col-md-4">

                                                    <label>ค้นหา</label>

                                                    <input type="text"
                                                        name="search"
                                                        value="{{ request('search') }}"
                                                        class="form-control form-control-solid"
                                                        placeholder="ชื่อผู้สมัคร / อีเมล">

                                                </div>

                                                <div class="col-md-3">

                                                    <label>สถานะ</label>

                                                    <select name="status"
                                                        class="form-select form-select-solid">

                                                        <option value="">
                                                            ทั้งหมด
                                                        </option>

                                                        <option value="pending"
                                                            {{ request('status')=='pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>

                                                        <option value="interview"
                                                            {{ request('status')=='interview' ? 'selected' : '' }}>
                                                            Interview
                                                        </option>

                                                        <option value="approved"
                                                            {{ request('status')=='approved' ? 'selected' : '' }}>
                                                            Approved
                                                        </option>

                                                        <option value="rejected"
                                                            {{ request('status')=='rejected' ? 'selected' : '' }}>
                                                            Rejected
                                                        </option>

                                                    </select>

                                                </div>

                                                <div class="col-md-2">

                                                    <button
                                                        style="margin-top:25px;"
                                                        class="btn btn-success w-100">

                                                        Search

                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                        <div class="table-responsive">

                                            <table class="table table-row-dashed align-middle">

                                                <thead>

                                                    <tr class="fw-bold text-gray-600">

                                                        <th width="5%">
                                                            #
                                                        </th>

                                                        <th>
                                                            ผู้สมัคร
                                                        </th>

                                                        <th>
                                                            งานที่สมัคร
                                                        </th>

                                                        <th>
                                                            เบอร์โทร
                                                        </th>

                                                        <th>
                                                            อีเมล
                                                        </th>

                                                        <th>
                                                            สถานะ
                                                        </th>

                                                        <th>
                                                            วันที่สมัคร
                                                        </th>

                                                        <th width="10%">
                                                            Action
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @forelse($items as $key => $item)

                                                        <tr>

                                                            <td>
                                                                {{ $items->firstItem() + $key }}
                                                            </td>

                                                            <td>

                                                                {{ $item->first_name }}
                                                                {{ $item->last_name }}

                                                            </td>

                                                            <td>

                                                                {{ $item->job->title_th ?? '-' }}

                                                            </td>

                                                            <td>

                                                                {{ $item->phone }}

                                                            </td>

                                                            <td>

                                                                {{ $item->email }}

                                                            </td>

                                                            <td>

                                                                @if($item->status == 'pending')

                                                                    <span class="badge badge-warning">
                                                                        Pending
                                                                    </span>

                                                                @elseif($item->status == 'interview')

                                                                    <span class="badge badge-info">
                                                                        Interview
                                                                    </span>

                                                                @elseif($item->status == 'approved')

                                                                    <span class="badge badge-success">
                                                                        Approved
                                                                    </span>

                                                                @elseif($item->status == 'rejected')

                                                                    <span class="badge badge-danger">
                                                                        Rejected
                                                                    </span>

                                                                @endif

                                                            </td>

                                                            <td>

                                                                {{ date('d/m/Y H:i', strtotime($item->created_at)) }}

                                                            </td>

                                                            <td>

                                                                <a href="{{ url("$segment/jobapplication/edit/$item->id") }}"
                                                                    class="btn btn-icon btn-light-primary btn-sm">

                                                                    <i class="ki-duotone ki-eye fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>

                                                                </a>

                                                            </td>

                                                        </tr>

                                                    @empty

                                                        <tr>

                                                            <td colspan="8"
                                                                class="text-center">

                                                                ไม่พบข้อมูล

                                                            </td>

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

</body>

</html>