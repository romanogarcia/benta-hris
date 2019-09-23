@extends('layouts.master')
@section('title', 'Holidays')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
        @include('includes.messages')
          <div class="card">
            <div class="card-header">
            ADD NEW HOLIDAY
            </div>
            <div class="card-body">
              <form method="post" action="{{ route('holidays.store') }}">
                @csrf
                <div class="form-group">
                  <label for="holiday">Name</label>
                  <input class="form-control @error('holiday') is-invalid @enderror" type="text" id="holiday" name="holiday" placeholder="Enter Holiday name">
                @error('holiday')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" id="description" placeholder="Enter Description">
                @error('description')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="type">Select Type</label>
                  <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                    <option value="">Select Type</option>
                    <option value="special">Special Holiday</option>
                    <option value="regular">Regular Holiday</option>
                  </select>
                @error('type')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="date">Select Date</label>
                  <input class="form-control @error('date') is-invalid @enderror is_datefield" type="text" id="date" name="date">
                @error('date')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
                </div>
              </form>
            </div>
          </div>   
        </div>
    </div>
</div>
@endsection