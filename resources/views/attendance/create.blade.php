@extends('layouts.master')
@section('title', 'Attendance')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
        @include('includes.messages')
            <div class="card">
                <div class="card-header">
                    Upload Attendance XLSX Format
                </div>
                <div class="card-body">
                @error('file')
                    <div class="alert alert-danger">
                        {{$message}}
                    </div>
                @enderror
                <form class="form-inline" method="post" action="{{ route('uploadcsv') }}" enctype="multipart/form-data">
                @csrf
                    <div class="form-group mx-sm-3 mb-2">
                        <input type="file" name="file" class="form-control-file is-invalid" id="inputfile" accept=".xls,.xlsx,.csv">
                    </div>
                    <button type="submit" class="btn btn-success mb-2 w-sm-100">Upload</button>
                    </form>
                    
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    Add Attendance Record
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" action="{{ route('attendance.store') }}"
                    aria-label="{{ __('Attendance') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="date">Date </label>
                            <input type="text" class="form-control @error('date') is-invalid @enderror is_datefield" id="date" name="date" value="{{ old('date') }}">
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
                                        <option value="{{ $employee->id }}">{{ ucwords($employee->first_name) }} {{ ucwords($employee->last_name) }}</option>
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
                                        value="{{ old('time_in') }}">
                                    @error('time_in')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group ">
                                    <label for="time_out">Time out </label>
                                    <input type="time" class="form-control @error('time_out') is-invalid @enderror" id="time_out" name="time_out"
                                        value="{{ old('time_out') }}">
                                    @error('time_out')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-check-flat form-check-primary">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="night_shift">
                            Night Shift
                          <i class="input-helper"></i></label><p><small class="help-block">Check if the current record is for night shift. It will add +1 day on timeout.</small></p>
                        </div> 
						<div class="text-right">
                        <button type="submit" class="btn btn-success full-width-xs"><i class="fa fa-save"> </i> SAVE</button>
						</div>	
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection