@extends('layouts.master')
@section('title', 'Add Module')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
      <div class="card-header">Add Module</div>
        <div class="card-body">

          <form method="POST" action="@if(@$module){{ route('module_table.update',@$module->id)}}@else{{ route('module_table.store') }} @endif" enctype="multipart/form-data" autocomplete="off">
            @csrf
			  @if(@$module)
			  @method('PATCH')
			  @endif
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="module_name">Module Name</label>
                    <input type="text" class="form-control form-control-sm" id="module_name" name="module_name" value="{{@$module->module_name}}">
                    @error('module_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
				<div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="module_link">Module Link</label>
                    <input type="text" class="form-control form-control-sm" id="module_link" name="module_link" value="{{@$module->module_link}}">
                    @error('module_link')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
				<div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="parent">Parent</label>
                    <select class="form-control form-control-sm" name="parent">
                        <option value="0">-- SELECT PARENT--</option>
						@foreach($modules as $singlemodule)
						@if($singlemodule->id == @$module->parent)
						<option value="{{$singlemodule->id}}" selected>{{$singlemodule->module_name}}</option>
						@else
						<option value="{{$singlemodule->id}}">{{$singlemodule->module_name}}</option>
						@endif
						@endforeach
                    </select>
                    @error('parent')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
				<div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="priority">Priority</label>
                    <input type="text" class="form-control form-control-sm" id="priority" name="priority" value="{{@$module->priority}}">
                    @error('priority')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
				<div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="route_name">Route Name</label>
                    <input type="text" class="form-control form-control-sm" id="route_name" name="route_name" value="{{@$module->route_name}}">
                    @error('route_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
				<div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="menu_icon">Menu Icon</label>
                    <input type="text" class="form-control form-control-sm" id="menu_icon" name="menu_icon"  value="{{@$module->menu_icon}}">
                    @error('menu_icon')
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
						@if(@$module)
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