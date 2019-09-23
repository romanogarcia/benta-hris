@extends('layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
        @include('includes.messages')
            <div class="card">
                <div class="card-header">
                    Update Attendance Record
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="{{ route('attendance.update',[$record->id]) }}"
                    aria-label="{{ __('Attendance') }}">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Date </label>
                            <input type="text" class="form-control @error('date') is-invalid @enderror is_datefield" id="date" name="date" value="{{ $record->at_date }}">
                            @error('date')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        @if(Auth::user()->employee_id == '1')
                            <div class="form-group">
                                <label for="name">Employee name </label>
                                <select class="form-control  @error('name') is-invalid @enderror" name="name" id="name">
                                    <option value="">-Select Employee-</option>
                                    @foreach($employees as $employee)
                                        <option @if($employee->id == $record->employee_id) selected @endif value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" class="form-control" id="name" placeholder="Enter Employee" name="name"
                                    value="{{ Auth::user()->employee_id }}">
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="time_in">Time in </label>
                                    <input type="time" class="form-control  @error('time_in') is-invalid @enderror" id="time_in" name="time_in"
                                        value="{{ date('H:i', strtotime($record->time_in)) }}">
                                    @error('time_in')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label for="time_out">Time out </label>
                                    <input type="time" class="form-control @error('time_out') is-invalid @enderror" id="time_out" name="time_out"
                                        value="{{ date('H:i', strtotime($record->time_out)) }}">
                                    @error('time_out')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-lg-6">
							  <div class="form-group ">
								<input type="hidden" name="user_id" value="{{ $user_id }}">
								<label for="state_status">Status</label>
								   <select class="form-control @error('state_status') is-invalid @enderror" name="state_status">
										@foreach($state_status as $row)
										<option value="{{ $row }}"  {{$row == $record->state_status ? 'selected':''}}>{{ $row }}</option>
										@endforeach
									</select>
								 	@error('state_status')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
							  </div>
							</div>
						</div>
						 <div class="form-check form-check-flat form-check-primary">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="night_shift" {{$record->night_shift == true ? __('checked'):__('')}} >
                            Night Shift
                          <i class="input-helper"></i></label><p><small class="help-block">Check if the current record is for night shift. It will add +1 day on timeout.</small></p>
                        </div> 
						
                        <div class="text-right">
                            <button type="submit" class="btn btn-success full-width-xs mt-1"><i class="fas fa-save"></i> UPDATE</button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection