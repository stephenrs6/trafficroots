@extends('layouts.app')
<!-- first commit test -->
@section('title','Library')
@section('css')
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
@endsection

@section('js')

@section('content')
    @if(Session::has('success'))
        <div id="alert_div" class="alert alert-success">
            <h4>{{ Session::get('success') }}</h4>
        </div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
			<!--
									<form name="library_form"
										  method="POST">
										<label>Dates</label>
										<div class="row">
											<div class="col-xs-12 form-group">
												<input hidden="true"
													   type="text"
													   name="daterange" />
												<div id="reportrange"
													 class="form-control">
													<i class="fa fa-calendar"></i>
													<span></span>
												</div>
											<label class="error hide"
												   for="dates"></label>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<div class="form-group">
													<button type="submit" class="btn btn-primary btn-block">Submit</button>
												</div>
											</div>

											<div class="col-xs-12 col-md-6">
												<div class="form-group">
													<button type="submit" class="btn btn-danger 	btn-block" id="resetFilter">Reset Filter</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

-->
			<div class="row">
				<div class="col-xs-12">
					<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li><a id="media_tab" href="#media-tab" data-toggle="tab">Media</a></li>
                        <li><a href="#link-tab" data-toggle="tab">Links</a></li>
                        <!-- @if($allow_folders)
                        <li><a href="#folder-tab" data-toggle="tab">Folders</a></li>
                        @endif -->
                    </ul>
                    <div id="my-tab-content" class="tab-content">
						<div class="tab-pane table-responsive active" id="media-tab">
							<div class="panel panel-body">
								<div class="btnNewEntry">
									<br>
									<div class="pull-right">@include('media_upload')</div>
									<br>
								</div>
								<div class="tableSearchOnly" id="media_div">
									@if (count($media))
									<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack" name="media_table" id="media_table">
										<thead>
											<tr>
												<th>Date</th>
												<th>Name</th>
												<th>Category</th>
												<th>Location Type</th>
												<th>Status</th>
												<th>Options</th>
												<th>Preview</th>
											</tr>
										</thead>
										<tbody>
										@foreach ($media as $file)
											<tr class="media_row" id="media_row_{{ $file->id }}">
												<td class="text-center"><b class=" tablesaw-cell-label">Date</b> {{ Carbon\Carbon::parse($file->created_at)->format('m/d/Y') }} </td>
												<td class="text-center"><b class=" tablesaw-cell-label">Name</b> {{ $file->media_name }} </td>
												<td class="text-center"><b class=" tablesaw-cell-label">Category</b> {{ $categories[$file->category] }} </td>
												<td class="text-center get_location_type_id"><b class=" tablesaw-cell-label">Location Type</b> {{ $location_types[$file->location_type] }} </td>
												<td class="text-center"><b class=" tablesaw-cell-label">Status</b><span class="currentStatus label"> {{ $status_types[$file->status] }} </span></td>
												<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
													<a href="{{ URL::to("/edit_media/$file->id") }}" >
														<button class="btn btn-xs btn-success alert-success">
														<span class="btn-label">
															<i class="fa fa-edit"></i>
														</span> Edit</button>
													</a>
												</td>
												<td class="text-center"><b class=" tablesaw-cell-label">Preview</b> <a href="#" class="tr-preview" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="" data-content="<img src='https://publishers.trafficroots.com/{{ $file->file_location }}' width='100%' height='auto'>" id="view_media_{{ $file->id }}"><i class="fa fa-camera" aria-hidden="true"></i></a> </td>
											</tr>
										@endforeach
										</tbody>
									</table>
									@else
										<h3>No Media Defined</h3>
									@endif
								</div>
							</div>
						</div>
						<div class="tab-pane table-responsive active" id="link-tab">
							<div class="ibox">
								<div class="panel panel-body" id="links_heading">
									<div class="btnNewEntry">
										<br>
										<div class="pull-right">@include('link_upload')</div>
										<br>
									</div>
									<div class="tableSearchOnly" id="links_div">
										@if (count($links))
										<table class="tablesaw tablesaw-stack table-striped table-hover dataTableSearchOnly dateTableFilter" data-tablesaw-mode="stack" name="links_table" id="links_table">
										   <thead>
												<tr>
													<th>Date</th>
													<th>Name</th>
													<th>Category</th>
													<th>URL</th>
													<th>Status</th>
													<th>Options</th>
												</tr>
											</thead>
											<tbody>
											@foreach ($links as $link)
												<tr class="link_row" id="link_row_{{ $link->id }}">
													<td class="text-center"><b class=" tablesaw-cell-label">Date</b> {{ Carbon\Carbon::parse($link->created_at)->format('m/d/Y') }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Name</b> {{ $link->link_name }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">Category</b> {{ $categories[$link->category] }} </td>
													<td class="text-center"><b class=" tablesaw-cell-label">URL</b> <a href="{{ $link->url }}" target="blank">{{substr($link->url,0,25)}}</a></td>
													<td class="text-center"><b class=" tablesaw-cell-label">Status</b><span class="currentStatus label"> {{ $status_types[$link->status] }} </span></td>
													<td class="text-center"><b class=" tablesaw-cell-label">Options</b>
														<a href="#"
														   class="link-edit"
														   data-toggle="modal"
														   data-target="#editLink{{ $link->id }}">
															<button class="btn btn-xs btn-success alert-success">
															<span class="btn-label">
																<i class="fa fa-edit"></i>
															</span> Edit</button>
														</a>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
										@else
											<h3>No Links Defined</h3>
										@endif
										{{-- <br /><br /><a href="/links"><button class="btn-u" type="button" id="add_link">Add Links</button></a> --}}
									</div>
								</div>
							</div>
							<!-- Modal -->
							<div id="myLinkModal" class="modal fade" role="dialog">
								<div class="modal-dialog modal-lg">
									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title" id="mytitle">Preview</h4>
										</div>
										<div class="modal-body" id="mybody">
											<p>Content</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						@if($allow_folders)
						<div class="tab-pane table-responsive active" id="folder-tab">
							<div class="ibox">
								<div class="ibox-title" id="creative_heading">My Folders
									<a href="/folder" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus-square-o"></i>&nbsp;Upload HTML5 Folder</a>
								</div>
								<div class="ibox-content table-responsive" id="folder_div">
									@if (count($folders))
									<table class="table table-hover table-border table-striped table-condensed" name="folders_table" id="folders_table" width="100%">
										<thead>
											<tr><th>Folder Name</th>
												<th>Category</th>
												<th>Location Type</th>
												<th>Status</th>
												<th>Date Uploaded</th>
												<th>Preview</th>
											</tr>
										</thead>
										<tbody>
										@foreach ($folders as $folder)
											<tr class="media_row" id="media_row_{{ $folder->id }}">
												<td>{{ $folder->folder_name }} </td>
												<td> {{ $categories[$folder->category] }} </td>
												<td> {{ $location_types[$folder->location_type] }} </td>
												<td> {{ $status_types[$folder->status] }} </td>
												<td> {{ Carbon\Carbon::parse($folder->created_at)->toDayDateTimeString() }} </td>
												<td> <a href="#" class="tr-iframe" data-toggle="modal" data-target="#myModal" id="view_folder_{{ $width[$folder->location_type] }}_{{ $height[$folder->location_type] }}_{{ $folder->file_location }}"><i class="fa fa-camera" aria-hidden="true"></a></i></td>
											</tr>
										@endforeach
										</tbody>
									</table>
									@else
										<h3>No Folders Defined</h3>
									@endif
								</div>
							</div>
						</div>
						@endif
                    </div>
				</div>
			</div>
        </div>
    </div>

@foreach ($links as $link)
<div class="editLink modal inmodal"
     id="editLink{{ $link->id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit URL</h4>
            </div>
            <form name="edit_link_form"
                  id="edit_link_form"
				  role="form"
				  method="POST"
				  action="{{ url('/edit_link/') }}"> {{ method_field('PATCH') }}
                    <div class="modal-body">
                        {{ csrf_field() }}
						<input type="hidden"
							   value = "{{ $link->id }}"
                               class="form-control"
                               name="link_id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text"
                               placeholder="Link name"
							   value = "{{ $link->link_name }}"
                               class="form-control"
                               name="link_name"
                               required>

                        <label class="error hide"
                               for="link_name"></label>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control m-b" id="link_category_id"
                                name="link_category"
                                required>
                            <option value="">Choose category</option>
                            @foreach(App\Category::all() as $category)
                            <option value="{{ $category->id }}"  {{ $link->category == $category->id ? 'selected="selected"' : '' }}>{{ $category->category }}</option>
                            @endforeach
                        </select>
                        <label class="error hide"
                               for="link_category"></label>
                    </div>

                    <div class="form-group">
                        <label>URL</label>
                        <input type="url"
                               placeholder="Must be a valid URL, with http:// or https://"
							   value = "{{ $link->url }}"
                               class="form-control"
                               name="url"
                               required>
                        <input type="hidden"
                               name="return_url"
                               id="return_url"
            @if( $_SERVER['REQUEST_URI'] == '/campaign')
                               value="campaign">
                        @else
                               value="library">
                    @endif
                        <label class="error hide"
                               for="url"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-white"
                            data-dismiss="modal">Cancel</button>
                    <button type="submit"
                            name="submit"
                            class="btn btn-primary">Submit</button>
                </div>
        </form>
        </div>
    </div>
</div>
@endforeach
<script type="text/javascript">
jQuery(document).ready(function ($) {
	$('.nav-click').removeClass("active");
	$('#nav_buyer_library').addClass("active");
	$('#nav_buyer').addClass("active");
	$('#nav_buyer_menu').removeClass("collapse");

	setStatus();
	$('#media_tab').click();

	$('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, pageLength: 10,
		responsive: true
	});
});

$("a.tr-preview").click(function(event){
    event.preventDefault();
});

@if(session()->has('media_updated'))
toastr.success("{{ Session::get('media_updated') }}");
@endif

@if(session()->has('link_updated'))
	toastr.success("{{ Session::get('link_updated') }}");
@endif

function setStatus() {
	var currentStatus = Array.from($(".currentStatus"));
	currentStatus.forEach(function(element) {
		if (element.innerText == "Active") {
		  element.classList.add("label-primary");
		} else if (element.innerText == "Declined") {
		  element.classList.add("label-danger");
		} else if (element.innerText == "Disabled") {
		  element.classList.add("label-default");
		} else {
		  element.classList.add("label-warning");
		};
	});
};

</script>
@endsection