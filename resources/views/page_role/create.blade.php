@extends('layouts.master')
@section('title', 'Add page and permission')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
      <div class="card-header">Add Page And Permissions</div>
        <div class="card-body">

          <form method="POST" action="@if(@$pagerole){{ route('page-role.update',@$pagerole->id)}}@else{{ route('page-role.store') }} @endif" enctype="multipart/form-data" autocomplete="off">
            @csrf
			  @if(@$pagerole)
			  @method('PATCH')
			  @endif
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="page_name">Page Name</label>
                    <select class="form-control form-control-sm" name="page_name" required>
                        <option value="">-- SELECT PAGE NAME--</option>
						@foreach($pages as $page)
						@if($page->module_name == @$pagerole->page_name)
						<option value="{{$page->module_name}}" selected>{{$page->module_name}}</option>
						@else
						<option value="{{$page->module_name}}">{{$page->module_name}}</option>
						@endif
						@endforeach
                    </select>
                    @error('page_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                  <label for="permission">Permission</label>
					<?php $editpermission=array();?>
					@if(@$pagerole)
						<?php $editpermission=explode('|',@$pagerole->permissions);?>
					@endif
					@if(in_array("full", @$editpermission))
					<div class="form-control form-control-sm"><input type="checkbox" class="" name="permission[]" value="full" id="allpermission" checked >ALL ACCESS</div>
					@else
					<div class="form-control form-control-sm"><input type="checkbox" class="" name="permission[]" value="full" id="allpermission">ALL ACCESS</div>
					@endif
					@foreach($permissions as $permission)
						@if(in_array($permission, @$editpermission))
							<div class="form-control form-control-sm"><input type="checkbox" class="permission" name="permission[]" value="{{$permission}}" checked="checked">{{$permission}}</div>
						@else
					<div class="form-control form-control-sm"><input type="checkbox" class="permission" name="permission[]" value="{{$permission}}">{{$permission}}</div>
						@endif
					@endforeach
                    @error('permission')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

            </div><!-- /row -->
                
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group text-right">
						@if(@$pagerole)
						<button type="submit" class="btn btn-success btn-sm w-sm-100"><i class="mdi mdi-check"></i> UPDATE </button> 
						@else
						<button type="submit" class="btn btn-success btn-sm w-sm-100"><i class="mdi mdi-check"></i> SAVE </button> 
						@endif
                        
                    </div>
                </div>
            </div>
            
          </form>    
        </div>
      </div>
    </div>
  </div> 
</div>
@endsection
@section('customjs')
<script>
$(document).ready(function(){
	$("#allpermission").click(function(){
		    if($(this).prop("checked") == true){
                $(".permission").prop("checked", true);
            }
            else if($(this).prop("checked") == false){
                $(".permission").prop("checked", false);
            }
  });
	$('.permission').click(function() {
		if ($(".permission:checked").length == $('.permission').length) {
		  $("#allpermission").prop("checked", true);
		} else {
		 $("#allpermission").prop("checked", false);
		}
	})
})
</script>
@endsection