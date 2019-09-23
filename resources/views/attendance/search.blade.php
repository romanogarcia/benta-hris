@extends('layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="content">

            @include('includes.messages')
            <div class="card mb-2">
                <div class="card-header">Attendance</div>
                <div class="card-body">
				<div class="row">
				  <div class="col-md-3 col-sm-4 col-xs-12">
					<a class="btn btn-block btn-primary btn-icon-text btn-sm" href="{{ route ('attendance.create') }}"><i class="mdi mdi-plus"></i> Add New Record</a>  
				  </div>
				</div>
				<br />
                <form method="GET" action="{{ route ('attendance.search') }}">
                    @if(Auth::user()->employee_id == '1')
                    <div class="form-group">
                        <select class="form-control" name="name[]" id="select2_names" multiple style="max-height: 200px;">
                          @foreach($employees as $employee)
                              <option 
                                @if(request()->has('name')) 
                                    @if(in_array($employee->id, request('name')))
                                        selected
                                    @endif
                                @endif
                                value="{{$employee->id}}"
                              >
                              {{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}
                              </option>
                          @endforeach
                      </select>
                    </div>
                    @else
                      <input class="form-control" type="hidden" name="name[]" value="{{ ucwords($current_employee->id) }}">
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="from_date">From</label>
                              <input class="form-control" type="date" id="from_date" name="from_date" value="{{ request()->has('from_date') ? request()->get('from_date') : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="from_date">To</label>
                              <input class="form-control" type="date" id="to_date" name="to_date" value="{{ request()->has('to_date') ? request()->get('to_date') : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-10"></div>
                      
                      <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" id="searchEmployee" class="btn btn-block btn-primary btn-icon-text btn-sm w-sm-100">
                                <i class="mdi mdi-account-search"></i>                                                    
                                Search
                            </button>
                        </div> 
                      </div>
                    </div>
                    </form>
                </div>
            </div> 
            <!-- /card -->
            <!-- <div class="row">
              <div class="col-lg-12">
                <div class="d-flex float-right">
                  <div class="p-2">
                    <form method="post" action="{{ route('proccessTimein') }}">
                      @csrf
                      <button type="submit" class="btn btn-success">TIME-IN</button>
                    </form>
                  </div>
                  <div class="p-2"><button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">TIME-OUT</button></div>
                </div>
              </div>
            </div> -->
            
            <div class="card">
                <div class="card-header">Attendance records</div>
                <div class="card-body">
					<div class="table-responsive" >
                    @if($record)
                    <table class="table" id="attendance_tabl">
                        <thead>
                            <th>Date</th>
                            <th>Name</th>
							<th>Status</th>
                            <th>Approved By</th>
                            <th>Time-In</th>
                            <th>Time-Out</th>
                            <th>Total</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                          @foreach($record as $r)
                            <tr>                           
                              <td>{{ $r->date}} @if($r->night_shift == true)<small><span class="badge badge-dark">night</span></small>@endif</td>
                              <td>{{ $r->name}}</td>
                              <td>{{ date('H:i', strtotime($r->time_in)) }} <abbr title="{{ date('h:i a @ D M-j-Y', strtotime($r->time_in)) }}"><small><em>{{ date('Y-m-d', strtotime($r->time_in))}}</em></small></abbr></td>
                              <td>
                              @if(strlen($r->time_out)!=1)
                              {{ date('H:i', strtotime($r->time_out))}} <abbr title="{{ date('h:i a @ D M-j-Y', strtotime($r->time_out)) }}"><small><em>{{ date('Y-m-d', strtotime($r->time_out))}}</em></small></abbr>
                              @else
                              --:-- 
                              @endif
                              </td>
                              <td>
                                <?php
                                  $time_in = strtotime($r->time_in);
                                  $time_out = strtotime($r->time_out);
                                  $total_time = $time_out - $time_in;

                                  echo ($r->time_out=='-')?'00:00:00' :gmdate("H:i:s", $total_time);
                                ?>
                              </td>
                              <td>
                                @if($r->updated_at)
                                  {{date('Y-m-d', strtotime($r->updated_at))}}
                                @endif
                              </td>
                              <td>
                                <div class="row col-lg">
                                  <div class="col">
                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{ route('attendance.edit', [$r->id]) }}';"><i class="mdi mdi-lead-pencil"></i></button>
                                  </div>
                                  <div class="col">
                                    <button type="submit" class='btn btn-outline-secondary btn-rounded btn-icon' data-toggle="modal" data-target="#modal-{{$r->id}}"><i class="mdi mdi-delete"></i></button>
                                  </div>
                                </div>
                              </td>
                            </tr>
                            <div class="modal fade" id="modal-{{$r->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalCenterTitle">Are you sure?</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Delete record <span class="badge badge-primary">{{$r->date}}</span> ? This can't be undone.
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary d-sm-block w-sm-100" data-dismiss="modal">Close</button>
                                  <form action="{{ route('attendance.destroy', [$r->id]) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                  <button type="submit" class="btn btn-danger d-sm-block w-sm-100">Delete</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          @endforeach
                        </tbody>
                        @else 
                        <div class="alert alert-warning">There are no records as of the moment</div>
                        @endif
                    </table>
                    @if($record)
                        {!! $record->appends(Request::except('page'))->render() !!}
                    @endif
					</div>	
                </div>
            </div>
    </div>
</div>
<!-- Modal -->
<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Timeout?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       Are you sure. You want to Time-out ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <form method="post" action="{{ route('proccessTimeout') }}">
        @csrf
        <input type="hidden" value="{{ Session::get('attendance_id') }}" name="att_id">
        <button type="submit" class="btn btn-danger">TIMEOUT</button>
        </form>
      </div>
    </div>
  </div>
</div>



@endsection

@section('javascript')
<!-- OPTIONAL SCRIPTS -->
<script src="/dist/plugins/chart.js/Chart.min.js"></script>
<script src="/dist/js/demo.js"></script>
<script src="/dist/js/pages/dashboard3.js"></script>


<!-- DataTables -->
<script src="/dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>
@endsection