@extends('layouts.master')
@section('title', 'Role Management')
@section('customcss')
<style>
  .table th, .table td{
    padding: 2px 2px 2px 10px;
  }
</style>
@endsection

@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
			Module  
        </div>
		<div class="card-header">
			<a href="{{route('module_table.create')}}">Create New</a>
        </div>
		  
        <div class="card-body">
          @if ($message = Session::get('success')) 
          <div class="alert alert-success" role="alert">
            <i class="mdi mdi-alert-circle"></i> 
            <strong>{{ $message }}</strong>
          </div>
          @endif

          @if ($errors->any())
          <div class="alert alert-danger">
              @foreach ($errors->all() as $error)        
              <i class="mdi mdi-alert-circle"></i>
              <strong>{{ $error }}</strong>
              @endforeach
          </div>
          @endif

          <div class="table-responsive" id="search_result_container">
            <table id="id-data_table" class="table">
             <thead>
                <tr>
                  <th>ID</th>
                  <th>Module Name</th>
                  <th>Module Link</th>
                  <th>Parent</th>
                  <th>Priority</th>
                  <th>Menu Icon</th>
                  <th>Route</th>
                  <th>Status</th>
                </tr>
             </thead>
			<tbody>
				<?php $i=1;?>
				@foreach($modules as $module)
				<tr>
					<td>{{$i}}</td>
					<td>{{$module->module_name}}</td>
					<td>{{$module->module_link}}</td>
					<td>{{$module->parent}}</td>
					<td>{{$module->priority}}</td>
					<td>{{$module->menu_icon}}</td>
					<td>{{$module->route_name}}</td>
					<td>{{$module->status}}</td>
					<td>
						<a href="{{route('module_table.edit',$module->id)}}">Edit</a>
						<form id="deleteForm-{{$module->id}}" action="{{route('module_table.destroy',$module->id)}}" method="post">
							{{ csrf_field() }}
                        {{ method_field('DELETE') }}
						<a href="javascript:" class="delete" data-id="{{$module->id}}" data-model="DeleteModal">Delete</a></form>
					</td>
				</tr>
				<?php $i++;?>
				@endforeach
			</tbody>
              
            </table>
          </div>

        </div>
      </div>
    </div><!-- /col-lg-12 -->
  </div>
</div>

@endsection

@section('customjs')
<script type="text/javascript" src="{{asset('plugins/jquery/jquery.cookie.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){
		$(".delete").click(function(){
			var id=$(this).attr('data-id');
			$('#deleteForm-'+id).submit()
		})
	})
</script>
@endsection