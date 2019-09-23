<!-- create.blade.php -->

@extends('layouts.master')
@section('title', 'Leave Request')
@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ $message }}</strong>
            </div>
      @elseif($message = Session::get('error'))
          <div class="alert alert-danger" role="alert">
              <i class="mdi mdi-alert-circle"></i>
              <strong>{{ $message }}</strong>
          </div>
      @endif
          <form class="sample-form" method="POST" action="{{ route('leaves.leaverequest') }}" enctype="multipart/form-data">
            <div class="form-group">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <input type="number" name="user_id" value="{{$user_id}}">
                    <label class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                      <select class="form-control" name="state_status" disabled>
                        @foreach($state_status as $row)
                        <option value="{{ $row }}">{{ $row }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Date</label>
                    <div class="col-sm-9">
                      <input type="text" id="date_file" value="{{ $today }}" class="form-control" name="date_filed" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Date Start</label>
                    <div class="col-sm-9">
                      <input type="date" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_start">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Date End</label>
                    <div class="col-sm-9">
                      <input type="date" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm" name="date_end">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-9">
                  <label for="name">Type of Leave</label>
                  <select class="form-control" name="type">
                    @foreach($leave_data as $row)
                    <option value="{{ $row->name }}">{{ $row->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <br />
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Reasons</span>
                </div>
                <textarea class="form-control" aria-label="Reasons" name="reason"></textarea>
              </div>
              <br />
              <a class='btn btn-warning' href="#"><i class="fa fa-remove"></i>Cancel</a>
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
        </div>
      </div>
    </div>
  </div>
</div>


@endsection