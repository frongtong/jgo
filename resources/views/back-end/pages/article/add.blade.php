
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
                                                    เพิ่ม{{ $moduleTitle ?? 'บทความ' }}
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="card-body">

                                            @if($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif

                                            <div class="row">

                                                <!-- ชื่อบทความ -->
                                                <div class="col-md-12 mb-5">
                                                    <label class="form-label">
                                                        ชื่อ{{ $moduleTitle ?? 'บทความ' }}
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="text"
                                                        class="form-control"
                                                        name="title"
                                                        value="{{ old('title') }}"
                                                        required>
                                                </div>

                                                @if($showCategories ?? true)
                                                <!-- หมวดหลัก -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        หมวดหมู่หลัก
                                                    </label>

                                                    <select
                                                        name="article_category1_id"
                                                        id="main_category_id"
                                                        class="form-select">

                                                        <option value="">
                                                            เลือกหมวดหมู่หลัก
                                                        </option>

                                                        @foreach($mainCategories as $category)

                                                        <option value="{{ $category->id }}"
                                                            {{ old('article_category1_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name_th }}
                                                        </option>

                                                        @endforeach

                                                    </select>

                                                </div>

                                                <!-- หมวดย่อย -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        หมวดหมู่ย่อย
                                                    </label>

                                                    <select
                                                        name="article_category2_id"
                                                        id="sub_category_id"
                                                        class="form-select">

                                                        <option value="">
                                                            เลือกหมวดหมู่ย่อย
                                                        </option>

                                                    </select>

                                                </div>

                                                @endif

                                                <!-- วันที่เผยแพร่ -->
                                                <div class="col-md-6 mb-5">

                                                    <label class="form-label">
                                                        วันที่เผยแพร่
                                                    </label>

                                                    <input type="datetime-local"
                                                        class="form-control"
                                                        name="published_at"
                                                        value="{{ old('published_at') }}">

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

                                                </div>

                                                <!-- รูป Banner -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        รูป Banner {{ ($multipleBanners ?? false) ? '(เลือกได้หลายรูป)' : '' }}
                                                    </label>

                                                    @if($multipleBanners ?? false)
                                                    <input type="file"
                                                        class="form-control"
                                                        name="banner_images[]"
                                                        accept=".jpg,.jpeg,.png,.webp"
                                                        multiple>
                                                    <small class="text-muted">
                                                        สามารถเลือกหลายไฟล์พร้อมกันได้
                                                    </small>
                                                    @else
                                                    <input type="file"
                                                        class="form-control"
                                                        name="banner_image"
                                                        accept=".jpg,.jpeg,.png,.webp">
                                                    @endif

                                                </div>

                                                <!-- คำอธิบายสั้น -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        คำอธิบายสั้น
                                                    </label>

                                                    <textarea
                                                        class="form-control"
                                                        rows="4"
                                                        name="short_description">{{ old('short_description') }}</textarea>

                                                </div>

                                                <!-- รายละเอียดบทความ -->
                                                <div class="col-md-12 mb-5">

                                                    <label class="form-label">
                                                        รายละเอียด{{ $moduleTitle ?? 'บทความ' }}
                                                    </label>

                                                    <textarea
                                                        id="description"
                                                        class="form-control"
                                                        rows="10"
                                                        name="description">{{ old('description') }}</textarea>

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
    CKEDITOR.replace('description', {
        fullPage: true,
        allowedContent: true,
        height: 320,
        versionCheck: false,
        extraPlugins: 'uploadimage',
        filebrowserBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html',
        filebrowserImageBrowseUrl: '/apps/ckfinder/3.4.5/ckfinder.html?type=Images',
        filebrowserUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/apps/ckfinder/3.4.5/core/connector/php/connector.php?command=QuickUpload&type=Images',
        removeButtons: 'PasteFromWord'
    });

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
