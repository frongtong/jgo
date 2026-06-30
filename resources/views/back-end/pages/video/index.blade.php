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

                                <div class="card card-flush">

                                    <div class="card-header align-items-center py-5">

                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                            <a href="{{ url("$segment/$folder/add") }}"
                                                class="btn btn-primary">
                                                Add
                                            </a>

                                        </div>

                                    </div>

                                    <div class="card-body pt-0">

                                        <form method="GET">

                                            <div class="row mb-5">

                                                <div class="col-md-6">

                                                    Title

                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="search"
                                                        value="{{ request('search') }}"
                                                        placeholder="Search Video">

                                                </div>

                                                <div class="col-md-4">

                                                    <button
                                                        style="margin-top:15px;"
                                                        class="btn btn-success">

                                                        Search

                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                        <div class="table-responsive">

                                            <table class="table align-middle table-row-dashed">

                                                <thead>

                                                    <tr>

                                                        <th width="5%" class="text-center">#</th>

                                                        <th width="10%" class="text-center">รูปปก</th>

                                                        <th>ชื่อ</th>

                                                        <th width="15%">หมวดหมู่หลัก</th>

                                                        <th width="15%">หมวดหมู่รอง</th>

                                                        <th width="15%">วันที่แสดง</th>

                                                        <th width="12%" class="text-center">สถานะ</th>

                                                        <th width="10%" class="text-center">
                                                            #
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @forelse($items as $index => $item)

                                                    <tr>

                                                        <td class="text-center">
                                                            {{ $items->pages->start + $index + 1 }}
                                                        </td>

                                                        <td class="text-center">

                                                            @if($item->cover_image_url)

                                                            <img
                                                                src="{{ asset($item->cover_image_url) }}"
                                                                width="60"
                                                                class="img-fluid rounded">

                                                            @endif

                                                        </td>

                                                        <td>
                                                            {{ $item->title }}
                                                        </td>

                                                        <td>

                                                            {{ optional($item->mainCategory)->name_th }}

                                                        </td>

                                                        <td>

                                                            {{ optional($item->subCategory)->name_th }}

                                                        </td>

                                                        <td>

                                                            {{ $item->published_at
                                                                ? \Carbon\Carbon::parse($item->published_at)->format('d/m/Y')
                                                                : '-'
                                                            }}

                                                        </td>

                                                        <td class="text-center">
                                                            <label class="form-check form-switch form-check-custom form-check-solid justify-content-center mb-0">
                                                                <input class="form-check-input update-status" type="checkbox" value="{{ $item->status }}" data-id="{{ $item->id }}" @if ($item->status == 'on') checked @endif>
                                                            </label>
                                                        </td>

                                                        <td class="text-center">


                                                            <!-- Edit -->
                                                            <a href="{{ url("$segment/$folder/edit/$item->id") }}"
                                                                class="btn btn-icon btn-light-warning btn-sm">

                                                                <i class="ki-duotone ki-pencil fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>

                                                            </a>
                                                            <!-- Delete -->
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

                                                        <td colspan="8" class="text-center">

                                                            No data found

                                                        </td>

                                                    </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                        <div class="table-footer mt-2">

                                            <div class="row">

                                                <div class="col-md-12">

                                                    {!! $items->appends(request()->all())
                                                    ->links('back-end.layout.pagination') !!}

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
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
    <!--end::Javascript-->

</body>
<!--end::Body-->
<script>
    var fullUrl = window.location.origin + window.location.pathname;

    $(document).ready(function() {
        $('.update-status').on('change', function() {
            var $checkbox = $(this);
            var id = $(this).data('id');
            var status = $(this).is(':checked') ? "on" : "off";
            var originalChecked = !$checkbox.is(':checked');

            $.ajax({
                url: fullUrl + "/update-status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (!response.status) {
                        $checkbox.prop('checked', originalChecked);
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่สำเร็จ',
                            text: 'ไม่สามารถอัปเดตสถานะได้',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'อัปเดตสถานะเรียบร้อย',
                        timer: 1200,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    $checkbox.prop('checked', originalChecked);
                    Swal.fire({
                        icon: 'error',
                        title: 'ไม่สำเร็จ',
                        text: 'ไม่สามารถอัปเดตสถานะได้',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    });

    function deleteItem(id) {
        Swal.fire({
            title: "ลบข้อมูล",
            text: "คุณต้องการลบข้อมูลใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(fullUrl + '/destroy', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status) {
                            Swal.fire("ลบแล้ว!", "ข้อมูลของคุณถูกลบแล้ว", "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("ล้มเหลว!", "ไม่สามารถลบข้อมูลได้", "error");
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    });
            }
        });
    }
</script>

</html>
