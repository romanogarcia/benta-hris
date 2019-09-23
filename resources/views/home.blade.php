@extends('layouts.master')
@section('title', 'Dashboard')
@section('customcss')
  <style>
  .icon-md.text-info.icon-employees{
    font-size: 5em;
    color: #4a494d !important;
  }
  .total-employees{
    background:  #004080;
    opacity: 0.9;
    filter: Alpha(opacity=50); /* IE8 and earlier */
    color: #fff;
  }
  .text-muted-color{
    color: white;
    font-weight: bold;
  }
  .card-title{
    font-size: 2em !important;
    color: #ffffff !important;
  }
  .card-text{
    color: #ffffff !important;
  }
  .card-text.leave-used{
    color: gray;
  }
  </style>
@endsection

@section('content')
<div class="content-wrapper">
@include('includes.messages')
<div class="row">
  <div class="col-md-4">
  <?php // print_r(getEmployeesLeaveBalance()); exit; ?>
      <h4 class="lead">Dashboard</h4>
  </div>
  <div class="col-md-8">
    <div class="d-flex float-right">
      <div class="p-2">
        <form method="post" action="{{ route('proccessTimein') }}">
          @csrf
<!--           <button type="submit" class="btn btn-success"><i class="mdi mdi-login"></i> TIME-IN</button> -->
        </form>
      </div>
<!--       <div class="p-2"><button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter"><i class="mdi mdi-logout"></i> TIME-OUT</button></div> -->
    </div>
  </div>
</div>

  @include('includes.employees-chart',['data' => getEmployeesDashboardReport()])

  <div class="row">
    <div class="col-md-5 grid-margin grid-margin-md-0 stretch-card">
      <div class="card">
        <div class="card-body text-center">
          <div>
            <img src="{{get_profile_picture()}}" class="img-lg rounded-circle mb-2" alt="profile image">
            <h4>{{ ucwords($get_current_user->first_name).' '.ucwords($get_current_user->last_name) }}</h4>
            <p class="text-muted mb-0">{{ ucfirst($get_current_user->position) }}</p>
          </div>

          <a href="{{ route('leave.leavecreate')}}" class="btn btn-success btn-sm mt-3 mb-4"><i class="mdi mdi-beach p-3"></i>Request Time Off</a>
          <div class="border-top pt-3">
            <p class="text-muted mb-0">Available Leave(s)</p>
            <br>
            <div class="row">
            
              <div class="col-6">
                <h6>{{getEmployeesLeaveBalance()->vacation_leave}}</h6>
                <p>Vacation Leave</p>
              </div>
              
              <div class="col-6">
                <h6>{{getEmployeesLeaveBalance()->sick_leave}}</h6>
                <p>Sick Leave</p>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7 grid-margin grid-margin-md-0 stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="">Leaves Used</h4><br/>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="pt-1 pl-0">Leave Type</th> <th class="pt-1"> Date Used </th><th class="pt-1">Others     </th>
                </tr>
              </thead>
              <tbody>
                @foreach($leave_requests as $leave_request)
                <tr>
                  <td class="py-1 pl-0">
                    <div class="d-flex align-items-center">
                      <div class="ml-3">
                        <p class="mb-0">{{ $leave_request->type }}</p>
                        <p class="mb-0 text-muted text-small">{{ $leave_request->reason }}</p>
                      </div>
                    </div>
                  </td>
                  <td>{{ date(get_date_format(), strtotime($leave_request->date_start)) }} to {{ date(get_date_format(), strtotime($leave_request->date_end ))}}</td>
                  <td>
                    <label class="badge badge-success">{{ $leave_request->state_status }}</label>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{$leave_requests->links()}}
          </div>
        </div>
      </div>
    </div>
  </div><!--/row-->
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
