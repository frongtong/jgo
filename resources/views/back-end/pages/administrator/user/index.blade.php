<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
        @include("$prefix.layout.head")
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
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
														<input type="text" class="form-control form-control-solid ps-10" id="keyword" name="keyword" value="{{@Request::get('keyword')}}" placeholder="Keywords">
													</div>
													<div class="col-md-2">
														Status
														<select id="status" name="status" class="form-select form-select-solid" >
															<option value="">All</option>
															<option value="Y" @if(@Request::get('status') == 'Y') selected @endif>Active</option>
															<option value="N" @if(@Request::get('status') == 'N') selected @endif>Inactive</option>
														</select>
													</div>
													<div class="col-md-4">
													   <button style="margin-top:15px;" class="btn btn-md btn-success">Search</button>
													</div>
												</div>
											</form>
											<!-- End Search -->

											<div class="hidden md:block mx-auto text-slate-500"><b>Showing {{$items->currentPage()}} to {{$items->total()}} of {{$items->total()}} entries</b></div>
												<div class="table-responsive">
													<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
														<thead>
															<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																<th style="width:5%;" class="text-center">#</th>
																<th style="width:35%;" class="text-left">Name</th>
																<th style="width:25%;" class="text-left">Email</th>
																<th style="width:25%;" class="text-left">Role</th>
																<th style="width:15%;" class="text-center">Created at</th>
																<th style="width:15%;" class="text-center">Updated at</th>
																<th style="width:10%;" class="text-center">Status</th>
																<th style="width:10%;" class="text-center">Action</th>
															</tr>
														</thead>
														<tbody>
															@if (@$items->count() > 0)
																@foreach (@$items as $index => $item)
																	<tr>
																		<td class="text-center"> {{ $items->pages->start+$index + 1 }}</td>
                                                                        <td>{{$item->name}}</td>
                                                                        <td>{{$item->email}}</td>
																		<td>{{$item->Role->name}}</td>
																		<td class="text-center">{{ date('d/m/Y, H:i',strtotime($item->created_at)) }}</td>
																		<td class="text-center">{{ date('d/m/Y, H:i',strtotime($item->updated_at)) }}</td>
																		<td class="text-center">
																			<label class="form-check form-switch form-check-custom form-check-solid" style="display: contents !important;">
																				<input class="form-check-input" type="checkbox" onclick="status({{$item->id}});" @if($item->isActive == 'Y') checked @endif>
																			</label>
																		</td>
																		<td class="text-center">
																			<a href="{{ url("$segment/$folder/edit/$item->id") }}"><i class="fa fa-edit fa-2x" style="margin-right:5px;"></i></a>
																			<a href="javascript:void(0);" onclick="deleteItem({{$item->id}})"><i class="fa fa-trash fa-2x" style="margin-right:5px;"></i></a>
																		</td>
																	</tr>
																@endforeach
															@else
																<tr>
																	<td colspan="7" class="text-center">- No items -</td>
																</tr>
															@endif

														</tbody>
													</table>
												</div>

												<div class="table-footer mt-2">
													<div class="row">
														<div class="col-sm-5">
															<p style=" ">

															</p>
														</div>
														<div class="col-sm-7" >
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
		<!--end::Javascript-->
		<script>
        var fullUrl = window.location.origin + window.location.pathname;

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
