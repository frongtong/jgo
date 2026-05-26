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
                                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                                            <a href="{{ url("$segment/$folder/add") }}" class="btn btn-primary">Add</a>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <!-- Search -->
                                        <form method="get">
                                            <div class="row mb-5">
                                                <div class="col-md-6">
                                                    Keywords
                                                    <input type="text" class="form-control form-control-solid ps-10" id="keyword" name="keyword" value="{{ @Request::get('keyword') }}" placeholder="Keywords">
                                                </div>
                                                <div class="col-md-2">
                                                    Status
                                                    <select id="status" name="status" class="form-select form-select-solid">
                                                        <option value="">All</option>
                                                        <option value="on" @if (@Request::get('status')=='on' ) selected @endif>Active</option>
                                                        <option value="off" @if (@Request::get('status')=='off' ) selected @endif>Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button style="margin-top:15px;" class="btn btn-md btn-success">Search</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- End Search -->

                                        <div class="hidden md:block mx-auto text-slate-500"><b>Showing
                                                {{ $items->currentPage() }} to {{ $items->total() }} of
                                                {{ $items->total() }} entries</b></div>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5">

                                                <thead>

                                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">

                                                        <th class="w-50px text-center">#</th>

                                                        <th>Member</th>

                                                        <th class="text-center w-150px">
                                                            Phone
                                                        </th>

                                                        <th class="text-center w-150px">
                                                            Status
                                                        </th>

                                                        <th class="text-center w-175px">
                                                            Apply Date
                                                        </th>

                                                        <th class="text-center w-200px">
                                                            Action
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody class="fw-semibold text-gray-600" id="sortable">

                                                    @forelse($items as $index => $item)

                                                    <tr data-id="{{ $item->id }}">

                                                        <!-- No -->
                                                        <td class="text-center">

                                                            {{ $items->firstItem() + $index }}

                                                        </td>

                                                        <!-- Member -->
                                                        <td>

                                                            <div class="d-flex align-items-center">

                                                                <!-- Image -->
                                                                <div class="symbol symbol-50px me-5">

                                                                    @if(@$item->profile->profile_image)

                                                                    <img src="{{ asset($item->profile->profile_image) }}"
                                                                        class="object-fit-cover">

                                                                    @else

                                                                    <div class="symbol-label bg-light-primary text-primary fs-3 fw-bold">

                                                                        {{ substr(@$item->profile->first_name_th,0,1) }}

                                                                    </div>

                                                                    @endif

                                                                </div>

                                                                <!-- Info -->
                                                                <div class="d-flex flex-column">

                                                                    <a href="{{ url("webpanel/member/view/$item->id") }}"
                                                                        class="text-gray-800 text-hover-primary fs-5 fw-bold">

                                                                        {{ @$item->profile->first_name_th }}
                                                                        {{ @$item->profile->last_name_th }}

                                                                    </a>

                                                                    <span class="text-muted fs-7">

                                                                        {{ $item->member_code }}

                                                                    </span>

                                                                    <span class="text-muted fs-7">

                                                                        {{ $item->email }}

                                                                    </span>

                                                                </div>

                                                            </div>

                                                        </td>

                                                        <!-- Phone -->
                                                        <td class="text-center">

                                                            {{ @$item->profile->phone ?? '-' }}

                                                        </td>

                                                        <!-- Status -->
                                                        <td class="text-center">

                                                            <select class="form-select form-select-sm"
                                                                onchange="changeStatus({{ $item->id }}, this.value)">

                                                             

                                                                <option value="pending"
                                                                    {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                                    Pending
                                                                </option>

                                                                <option value="active"
                                                                    {{ $item->status == 'active' ? 'selected' : '' }}>
                                                                    Approved
                                                                </option>

                                                                <option value="inactive"
                                                                    {{ $item->status == 'inactive' ? 'selected' : '' }}>
                                                                   Inactive
                                                                </option>

                                                            </select>

                                                        </td>

                                                        <!-- Apply Date -->
                                                        <td class="text-center">

                                                            @if($item->apply_date)

                                                            {{ date('d/m/Y H:i', strtotime($item->apply_date)) }}

                                                            @else

                                                            -

                                                            @endif

                                                        </td>

                                                        <!-- Action -->
                                                        <td class="text-center">

                                                            <div class="d-flex justify-content-center gap-2">

                                                                <!-- View -->
                                                                <a href="{{ url("webpanel/member/view/$item->id") }}"
                                                                    class="btn btn-icon btn-light-info btn-sm">

                                                                    <i class="ki-duotone ki-eye fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                        <span class="path3"></span>
                                                                    </i>

                                                                </a>

                                                                <!-- Edit -->
                                                                <a href="{{ url("webpanel/member/edit/$item->id") }}"
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

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    @empty

                                                    <tr>

                                                        <td colspan="6">

                                                            <div class="d-flex flex-column flex-center py-20">

                                                                <img src="{{ asset('backend/assets/media/illustrations/sketchy-1/5.png') }}"
                                                                    class="mw-300px mb-10">

                                                                <div class="fs-1 fw-bold text-gray-500">
                                                                    No Member Found
                                                                </div>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                    @endforelse

                                                </tbody>

                                            </table>
                                        </div>



                                        <div class="table-footer mt-2">
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <p style=" ">

                                                    </p>
                                                </div>
                                                <div class="col-sm-7">
                                                    {!! $items->appends(request()->all())->links('back-end.layout.pagination') !!}
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

    <script>
        var fullUrl = window.location.origin + window.location.pathname;

   

function changeStatus(id, status)
{
    $.ajax({

        url: "{{ url('webpanel/member/update-status') }}",

        type: "POST",

        data: {
            _token : "{{ csrf_token() }}",
            id : id,
            status : status
        },

        success: function(res)
        {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Update status successfully',
                timer: 1200,
                showConfirmButton: false
            });
        }

    });
}


        function deleteItem(ids) {
            const id = [ids];
            if (id.length > 0) {
                destroy(id)
            }
        }

        function destroy(id) {
            Swal.fire({
                title: "Delete",
                text: "Do you want to delete your item ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(fullUrl + '/destroy/' + id)
                        .then(response => response.json())
                        .then(data => location.reload())
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`)
                        })
                }
            });
        }
    </script>
</body>
<!--end::Body-->

</html>