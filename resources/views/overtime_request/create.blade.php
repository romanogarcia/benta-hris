@extends('layouts.master')

@section('title', 'Overtime Request')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="content-wrapper">
    <div class="grid-margin stretch-card">
      <div class="card">
      <div class="card-header">Overtime Request Form</div>
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
            <strong>{{ $error }}</strong><br>
            @endforeach
          </div>
          @endif

          
          <form class="sample-form" method="POST" action="{{ route('overtime.store') }}" enctype="multipart/form-data">
            <div class="form-group">
              @csrf
              <div class="row">
                <div class="col-md-6 d-none">
                  <div class="form-group row">
                    <input type="number" name="user_id" value="{{ Auth::user()->employee_id }}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                <div class="form-group row">
                    <label for="date_start" class="col-sm-3 col-form-label">Date Start</label>
                    <div class="col-sm-9">
                      <input type="text" required="true" value="{{ old('date_start') }}" id="date_start"  data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm is_datefield" name="date_start">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time_start" class="col-sm-3 col-form-label">Time Start</label>
                    <div class="col-sm-9">
                      <input type="time" required="true" value="{{ old('time_start') }}" id="time_start" class="form-control form-control-sm" name="time_start">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="date_end" class="col-sm-3 col-form-label">Date End</label>
                    <div class="col-sm-9">
                      <input type="text" required="true" value="{{ old('date_end') }}" id="date_end" data-date="" data-date-format="DD MMMM YYYY" class="form-control form-control-sm is_datefield" name="date_end">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="time_end" class="col-sm-3 col-form-label">Time End</label>
                    <div class="col-sm-9">
                      <input type="time" required="true" value="{{ old('time_end') }}" id="time_end" class="form-control form-control-sm" name="time_end">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <label for="type">Type of Overtime</label>
                  <select class="form-control" required="true" name="type" id="type">
                    @foreach($overtime_type as $ot_type)
                      @if($ot_type == old('type'))
                        <option selected="true" value="{{ $ot_type }}">{{ $ot_type }}</option>
                      @else
                        <option value="{{ $ot_type }}">{{ $ot_type }}</option>
                      @endif;
                    @endforeach
                  </select>
                </div>
              </div>
              <br />
              <div class="input-group pb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Reasons</span>
                </div>
                <textarea class="form-control" required="true" style="height: 100px;" aria-label="Reasons" name="reason">{{ old('reason') }}</textarea>
              </div>
              <button type="submit" class="btn btn-success float-right w-sm-100">
                <i class="fa fa-save"></i>&nbsp;Save
			  </button>
            </div>
          </form>
          
        </div>
      </div>
    </div>
</div>
@endsection