<!-- create.blade.php -->

@extends('layouts.master')
@section('title', 'Request New Leave')
@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="content-wrapper">
  <div class="row">
    <div class="col grid-margin stretch-card">
      <div class="card">
        <div class="card-header">Request New Leave</div>
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

 
          <form class="sample-form" method="POST" action="{{ route('leave.leaverequest') }}" enctype="multipart/form-data">
            <div class="form-group">
              @csrf
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date</label>
                    <div class="form-control form-control-sm">{{date(get_date_format(), strtotime($today))}}</div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="date_start">Date Start</label>
                      <input type="text" id="date_start" data-date="" data-date-format="{{ get_date_format_label()}}" class="form-control form-control-sm is_datefield" name="date_start">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="date_end">Date End</label>
                      <input type="text" id="date_end" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm is_datefield" name="date_end">
                  </div>
                </div>
              </div>
			<div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
						<input id="half_day" type="checkbox" name="half_day" value="1" autocomplete="off">
						Half-Day 
					  <i class="input-helper"></i><i class="input-helper"></i></label>
                </div>
              </div>
			</div>	
              <div class="row">
                <div class="col-lg">
                  <label for="reason">Reasons</label>
                  <textarea class="form-control" style="height: 150px;" aria-label="Reasons" name="reason"></textarea>
                </div>
                <div class="col-lg">
                  <label for="reason">Comments</label>
                  <textarea class="form-control" style="height: 150px;" aria-label="Reasons" name="comments" readonly></textarea>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-6">
                  <label for="name">Type of Leave</label>
                  <select class="form-control" name="type">
                    @foreach($leave_data as $row)
                      <option value="{{ $row->name }}">{{ $row->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <br />
              <div class="form-group" style="display: none;">
                <div class="input-group">
                  <input type="file" class="form-control form-control-sm" placeholder="Name" name="select_file" autocomplete="off" style="text-transform: uppercase;">
                  <div class="input-group-append">
                  </div>
                </div>
              </div>
              <br />

              <button class="btn btn-success float-right w-sm-100" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
 
</div>



@endsection

