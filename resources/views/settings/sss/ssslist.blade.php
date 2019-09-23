@extends('layouts.master')
@section('title', 'SSS')
@section('content')

<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
          <div class="card-header">
              SSS
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

          <div class="row">
			  @if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Add")))
              <div class="col-md-6 col-lg-4 mrt-sm-2">
                    <a href="{{route('sss.create')}}" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i> ADD NEW SSS TABLE</a>
              </div>
              <div class="col-md-6 col-lg-4 mrt-sm-2">
                  <button type="button" data-target="#uploadModal" data-toggle="modal" class="btn btn-primary btn-block w-sm-100"><i class="mdi mdi-library-plus"></i>&nbsp;&nbsp;&nbsp; UPLOAD NEW FILE &nbsp;&nbsp;&nbsp;</button>
              </div>
			  @endif
              <div class="col-md-12 col-lg-4 mt-3 mt-lg-0">
                  <form action="?" method="get" class="form-inline justify-content-end m-0">
                   <input type="hidden" value="{{$rows->currentPage()}}" name="page">
                  <label class="tbl_length_lbl">Show </label>
                  
                   <select class="form-control form-control-sm tbl_length_select" name="perpage" onchange="this.form.submit()" >
                     <option value="100" {{app('request')->input('perpage') == 100 ? 'selected':''}}>100</option>
                     <option value="200" {{app('request')->input('perpage') == 200 ? 'selected':''}}>200</option>
                     <option value="500" {{app('request')->input('perpage') == 500 ? 'selected':''}}>500</option>
                     <option value="1000" {{app('request')->input('perpage') == 1000 ? 'selected':''}}>1000</option>
                   </select>
					   <label class="tbl_length_lbl">entries</label>
                 </form>
              </div>
          </div>
          <div class="mrt-sm-3 mt-3"></div>
            <!-- @include('includes.perpage') -->
			@if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","View")))
          <div class="table-responsive">
            <table class="table table-bordered" id="sss_table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th> Min </th>
                  <th> Max </th>
                  <th> Salary </th>
                  <th> EC </th>
                  <th> Total Contribution Employer </th>
                  <th> Total Contribution Employee </th>
                  <th> Total Contribution </th>
                  <th colspan="2">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rows as $row)
                <tr>
                  <td>@if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Edit")))
					  <a href="{{route('sss.edit',[$row->id])}}">{{$row->id}}</a>
					  @else 
					  {{$row->id}}
					  @endif</td>
                  <td>{{ $row->min }}</td>
                  <td>{{ $row->max }}</td>
                  <td>{{ $row->salary }}</td>
                  <td>{{ $row->sss_ec_er }}</td>
                  <td>{{ $row->total_contribution_er }}</td>
                  <td>{{ $row->total_contribution_ee }}</td>
                  <td>{{ $row->total_contribution_total }}</td>
                    <td>
						@if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Edit")))
                        <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{route('sss.edit',[$row->id])}}';"><i class="mdi mdi-lead-pencil"></i></button>
						@endif
                    </td>
                    <td>
						@if((check_permission(Auth::user()->Employee->department_id,"SSS","full")) || (check_permission(Auth::user()->Employee->department_id,"SSS","Delete")))
                        <button type="button" data-target="#DeleteModal{{$row->id}}" data-toggle="modal" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm"><i class="mdi mdi-delete"></i></button>
						@endif
                    </td>
                </tr>
                <!-- DELETE -->
                <div id="DeleteModal{{$row->id}}" class="modal fade text-default" role="dialog">
                    <div class="modal-dialog ">
                        <!-- Modal content-->
                        <form action="{{ route('sss.destroy',[$row->id])}}" id="deleteForm" method="post">
                            <div class="modal-content">
                                <div class="modal-header bg-default">

                                    <h4 class="modal-title text-center">REMOVE SSS TABLE?</h4>
                                </div>
                                <div class="modal-body">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <p>Do you want to really remove? This request can't be removed anymore.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                    <button type="submit" name="" class="btn btn-danger">Yes, Remove</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
              </tbody>
            </table>
          </div>
			@endif
            <div class="row mt-3">
                <div class="col-lg text-sm-center">
                    {!!  $entries !!}
                </div>
                <div class="col-lg">
                    <div class="float-lg-right justify-content-sm-center">
                        {{$rows->links()}}
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div><!-- /col-lg-12 -->
  </div>
</div>

<div id="uploadModal" class="modal fade text-default" role="dialog">
    <div class="modal-dialog ">
        <!-- Modal content-->
        <form action="" id="deleteForm" method="post">
            <div class="modal-content">
                <div class="modal-header bg-default">

                    <h4 class="modal-title text-center">UPLOAD SSS TABLE</h4>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <p>
                    Upload the SSS table in csv format
                    </p>
                    <form method="POST" action="{{ route('sss.store') }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" class="form-control form-control-sm" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="mdi mdi-library-plus"></i>&nbsp;&nbsp;&nbsp; UPLOAD NEW FILE &nbsp;&nbsp;&nbsp;</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<script type="text/javascript">
function deleteData(id)
{
   var id = id;
   var url = '{{ route("sss.destroy", ":id") }}';
   url = url.replace(':id', id);
   $("#deleteForm").attr('action', url);
}

function formSubmit()
{
   $("#deleteForm").submit();
}
</script>
