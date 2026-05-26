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
                                <div id="kt_app_content_container" class="app-container  container-xxl ">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title text-center py-3">
                                                <h5>หมวดหมู่รอง</h5>
                                            </div>
                                            <div class="container-fluid">
                                                <div class="row mb-3">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ชื่อ_ไทย<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="ชื่อ_ไทย" name="name_th" id="name_th" value="{{$data->name_th}}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">ชื่อ_อังกฤษ<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" placeholder="ชื่อ_อังกฤษ" name="name_en" id="name_en" value="{{$data->name_en}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mb-4">
                                                <div class="card-header bg-dark">
                                                    <h5 class="mt-5 text-white">เว็บไซต์สำหรับการติดตาม</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12 mb-6">
                                                        <div id="form-container-Url">
                                                            @foreach ($data->category2_link as $item)
                                                                <div class="input-group mb-3">
                                                                    <div class="col-md-12 mb-3">
                                                                        <label class="form-label">URL Shop<span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control mr-2" name="urls[]" placeholder="Enter URL" value="{{ $item->url }}" required />
                                                                    </div>
                                                                    <div class="col-md-12 mb-3">
                                                                        <label class="form-label">รูปแพลตฟอร์ม<span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">* รองรับไฟล์ (svg) เท่านั้น</span></label>
                                                                        <p>ชื่อไฟล์ : <b class="text-danger"> {{ basename($item->image) }}</b></p>
                                                                        <input type="file" class="form-control" name="images[]" accept="image/*" value="{{ asset($item->image) }}" accept="image/svg"/>
                                                                        <img src="{{ asset($item->image) }}" style="width:80px" class="img-fluid mt-3" alt="Preview" />
                                                                    </div>
                                                                    <input type="hidden" name="id_link[]" value="{{ $item->id }}">
                                                                    <button type="button" class="btn btn-danger" onclick="delete_path_img_url({{ $item->id }})">Remove</button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button id="addFormUrl" type="button" class="btn btn-dark shadow-md ml-2 mb-3">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex justify-content-end mt-5">
                                                <a href="{{ url("$segment/$folder/$category1_id") }}" id="" class="btn btn-light me-2">Cancel</a>
                                                <button type="submit" class="btn btn-primary" style="background: #1C2842;"><span class="indicator-label">Save Changes</span></button>
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
    <script>

        // เว็บไซต์สำหรับการติดตาม
        let formCountUrl = 1;
        document.getElementById("addFormUrl").addEventListener("click", function() {
            const divUrl = document.createElement("div");
            divUrl.setAttribute("id", `url${formCountUrl}`);
            divUrl.classList.add('mb-3');

            divUrl.innerHTML = `<div class="input-group mb-3">
                <div class="col-md-12 mb-3">
                    <label class="form-label">URL<span class="text-danger">*</span></label>
                    <input type="text" class="form-control mr-2" name="urls[]" placeholder="Enter URL" required />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">รูปแพลตฟอร์ม<span class="badge badge-light-danger fw-bold fs-8 px-2 py-1 ms-2">* รองรับไฟล์ (svg) เท่านั้น</span></label>
                        <input type="file" class="form-control" name="images[]" accept="image/svg" required />
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeFormUrl(${formCountUrl})">Remove</button>
                    </div>`;
                    
            document.getElementById("form-container-Url").appendChild(divUrl);
            formCountUrl++;
        });

        function removeFormUrl(num) {
            const divUrl = document.getElementById(`url${num}`);
            if (divUrl) {
                divUrl.remove();
            }
        }

        function delete_path_img_url(id) {
            Swal.fire({
                title: 'Do you want to delete your item ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/webpanel/category2/destroy/url') }}",
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Data has been successfully deleted.',
                                    confirmButtonColor: "#DD6B55",
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Something weng wrong.',
                                    confirmButtonColor: "#DD6B55",
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Something weng wrong.',
                                confirmButtonColor: "#DD6B55",
                            });
                        }
                    });
                }
            });
        }
    </script>
    <!--end::Javascript-->

</body>
<!--end::Body-->
<script>

</script>
</html>
