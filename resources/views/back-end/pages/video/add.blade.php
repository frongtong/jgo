```php
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

            <div id="kt_app_header"
                class="app-header"
                data-kt-sticky="true"
                data-kt-sticky-activate="{default: true, lg: true}"
                data-kt-sticky-name="app-header-minimize"
                data-kt-sticky-offset="{default: '200px', lg: '0'}"
                data-kt-sticky-animation="false">

                @include("$prefix.layout.head-menu")

            </div>

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                @include("$prefix.layout.side-menu")

                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">

                    <div id="kt_app_toolbar_container"
                        class="app-container container-xxl d-flex flex-stack">

                        @include("$prefix.layout.breadcrumbs")

                    </div>

                </div>

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                    <div class="d-flex flex-column flex-column-fluid">

                        <div id="kt_app_content"
                            class="app-content flex-column-fluid">

                            <form id="form_submit"
                                action="{{ url("$segment/$folder/add") }}"
                                method="POST"
                                enctype="multipart/form-data">

                                @csrf

                                <div id="kt_app_content_container"
                                    class="app-container container-xxl">

                                    <div class="card">

                                        <div class="card-header border-0 pt-6">

                                            <div class="card-title">

                                                <h3 class="fw-bold">
                                                    Add Video
                                                </h3>

                                            </div>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <!-- Title -->
                                                <!-- ชื่อบทความ -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        ชื่อบทความ
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        name="title"
                                                        placeholder="กรอกชื่อบทความ"
                                                        required>

                                                </div>

                                                <!-- หมวดหมู่หลัก -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        หมวดหมู่หลัก
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <select
                                                        name="main_category_id"
                                                        id="main_category_id"
                                                        class="form-select"
                                                        required>

                                                        <option value="">
                                                            เลือกหมวดหมู่หลัก
                                                        </option>

                                                        @foreach($mainCategories as $category)

                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name_th }}
                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                <!-- หมวดหมู่ย่อย -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        หมวดหมู่ย่อย
                                                    </label>

                                                    <select
                                                        name="sub_category_id"
                                                        id="sub_category_id"
                                                        class="form-select">

                                                        <option value="">
                                                            เลือกหมวดหมู่ย่อย
                                                        </option>

                                                    </select>

                                                </div>

                                                <!-- วันที่เผยแพร่ -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        วันที่เผยแพร่
                                                    </label>

                                                    <input type="datetime-local"
                                                        class="form-control"
                                                        name="published_at">

                                                </div>

                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        สถานะ
                                                    </label>

                                                    <select
                                                        class="form-select"
                                                        name="status">

                                                        <option value="on" {{ old('status', 'on') == 'on' ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>

                                                        <option value="off" {{ old('status') == 'off' ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>

                                                    </select>

                                                </div>

                                                <!-- รูปปก -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        รูปปก
                                                    </label>

                                                    <input type="file"
                                                        class="form-control"
                                                        name="cover_image"
                                                        accept=".jpg,.jpeg,.png,.webp">

                                                    <small class="text-muted">
                                                        รองรับไฟล์ jpg, jpeg, png และ webp
                                                    </small>

                                                </div>
                                                <!-- Youtube URL -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        ลิงก์ Youtube
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input
                                                        type="url"
                                                        class="form-control"
                                                        name="youtube_url"
                                                        placeholder="https://www.youtube.com/watch?v=xxxxxxxx">

                                                    <small class="text-muted">
                                                        รองรับลิงก์ Youtube เช่น https://www.youtube.com/watch?v=xxxxxxxx
                                                    </small>

                                                </div>

                                                <!-- คำอธิบาย -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        คำอธิบาย
                                                    </label>

                                                    <textarea
                                                        class="form-control"
                                                        rows="5"
                                                        name="description"
                                                        placeholder="รายละเอียดหรือคำอธิบายบทความ"></textarea>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="d-flex justify-content-end mt-5">

                                                <a href="{{ url("$segment/$folder") }}"
                                                    class="btn btn-light me-2">

                                                    Cancel

                                                </a>

                                                <button type="submit"
                                                    class="btn btn-primary"
                                                    style="background:#1C2842;">

                                                    Save Changes

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            <div id="kt_scrolltop"
                class="scrolltop"
                data-kt-scrolltop="true">

                <i class="ki-duotone ki-arrow-up">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>

            </div>

        </div>

    </div>

    @include("$prefix.layout.script")

</body>

<script>
    $('#main_category_id').change(function() {

        let id = $(this).val();

        $('#sub_category_id').html(
            '<option value="">Loading...</option>'
        );

        $.ajax({

            url: "{{ url($segment.'/'.$folder.'/subcategory') }}/" + id,

            type: 'GET',

            success: function(response) {

                let html =
                    '<option value="">Select Sub Category</option>';

                $.each(response, function(index, item) {

                    html +=
                        '<option value="' + item.id + '">' +
                        item.name_th +
                        '</option>';

                });

                $('#sub_category_id').html(html);

            }

        });

    });
</script>

</html>
```
