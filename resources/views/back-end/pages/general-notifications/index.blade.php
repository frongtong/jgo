<!DOCTYPE html>
<html lang="en">
<head>
    @include("$prefix.layout.head")
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
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
                                    <div class="card-header align-items-center py-5">
                                        <div class="card-title">
                                            <form method="GET" class="d-flex gap-3">
                                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="ค้นหาแจ้งเตือน">
                                                <button class="btn btn-success">Search</button>
                                            </form>
                                        </div>
                                        <div class="card-toolbar">
                                            <a href="{{ url("$segment/$folder/add") }}" class="btn btn-primary">Add</a>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed">
                                                <thead>
                                                    <tr class="fw-bold text-gray-600">
                                                        <th width="5%" class="text-center">#</th>
                                                        <th>หัวข้อ</th>
                                                        <th width="18%">วันที่เริ่ม</th>
                                                        <th width="18%">วันที่สิ้นสุด</th>
                                                        <th width="10%" class="text-center">สถานะ</th>
                                                        <th width="12%" class="text-center">จัดการ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($items as $index => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $items->pages->start + $index + 1 }}</td>
                                                            <td>
                                                                <div class="fw-bold">{{ $item->title }}</div>
                                                                <div class="text-muted fs-7">{{ $item->detail ?: '-' }}</div>
                                                            </td>
                                                            <td>{{ optional($item->start_date)->format('d/m/Y') }}</td>
                                                            <td>{{ optional($item->end_date)->format('d/m/Y') }}</td>
                                                            <td class="text-center">
                                                                <label class="form-check form-switch form-check-custom form-check-solid justify-content-center mb-0">
                                                                    <input class="form-check-input update-status" type="checkbox" data-id="{{ $item->id }}" @if($item->status == 'on') checked @endif>
                                                                </label>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ url("$segment/$folder/edit/$item->id") }}" class="btn btn-icon btn-light-warning btn-sm">
                                                                    <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                                </a>
                                                                <button type="button" onclick="deleteItem({{ $item->id }})" class="btn btn-icon btn-light-danger btn-sm">
                                                                    <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center">No data found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include("$prefix.layout.script")
    <script>
        $('.update-status').on('change', function () {
            $.post('{{ url("$segment/$folder/update-status") }}', {
                _token: '{{ csrf_token() }}',
                id: $(this).data('id'),
                status: $(this).is(':checked') ? 'on' : 'off'
            });
        });

        function deleteItem(id) {
            if (!confirm('Confirm delete?')) return;

            $.post('{{ url("$segment/$folder/destroy") }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }).done(function () {
                location.reload();
            });
        }
    </script>
</body>
</html>
