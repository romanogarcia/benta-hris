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
          User Roles
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
                  <th>Page</th>
                  <th>Permissions</th>
                  <th>Action</th>
                </tr>
             </thead>
			<tbody>
				<?php $i=1;?>
				@foreach($pageroles as $pagerole)
				<tr>
					<td>{{$i}}</td>
					<td>{{$pagerole->page_name}}</td>
					<td>{{$pagerole->permissions}}</td>
					<td>
						<a href="{{route('page-role.edit',$pagerole->id)}}">Edit</a>
						<form id="deleteForm-{{$pagerole->id}}" action="{{route('page-role.destroy',$pagerole->id)}}" method="post">
							{{ csrf_field() }}
                        {{ method_field('DELETE') }}
						<a href="javascript:" class="delete" data-id="{{$pagerole->id}}" data-model="DeleteModal">Delete</a></form>
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