@extends('layouts.master')
@section('title', 'Holidays')
@section('content')

<div class="content-wrapper">
    <div class="content">
    @include('includes.messages')
        <div class="container-fluid">
          <div class="card">
            <div class="card-header">
            Update Holiday
            
            </div>
            <div class="card-body">
              <form role="form" method="post" action="{{ route('holidays.update',[$holiday->id]) }}">
                @method('PUT')
                @csrf
                <div class="form-group">
                  <label for="holiday">Name</label>
                  <input class="form-control @error('holiday') is-invalid @enderror" type="text" id="holiday" name="holiday" placeholder="Enter Holiday name" value="{{ $holiday->name }}">
                @error('holiday')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <input class="form-control @error('description') is-invalid @enderror" type="text" name="description" id="description" placeholder="Enter Description" value="{{ $holiday->description }}">
                @error('description')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="type">Select Type</label>
                  <select class="form-control form-control-sm @error('type') is-invalid @enderror" name="type" id="type">
                    <option value="">Select Type</option>
                    <option value="special" {{ $holiday->type == 'special' ? 'selected':'' }}>Special Holiday</option>
                    <option value="regular" {{ $holiday->type == 'regular' ? 'selected':'' }} >Regular Holiday</option>
                  </select>
                @error('type')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-group">
                  <label for="date">Select Date</label>
                  <input class="form-control @error('date') is-invalid @enderror is_datefield" type="text" id="date" name="date" value="{{ $holiday->holiday_date}}">
                @error('date')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
                </div>
                <div class="form-check pl-3">
                
                  <input class="form-check-input" type="checkbox" id="defaultCheck1" name="status" @if($holiday->status==true) checked @endif>
                  <label class="form-check-label" for="defaultCheck1">
                    ACTIVE
                  </label>
                </div>
                <div class="form-check pl-3">
                
                  <input class="form-check-input" type="checkbox" id="defaultCheck2" name="status">
                  <label class="form-check-label" for="defaultCheck1">
                    Recurent Date
                  </label>
                </div>
                <div class="form-group pt-3 text-right">
                  <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-check"></i> UPDATE</button>
                </div>
              </form>
            </div>
          </div>   
        </div>
    </div>
</div>
@endsection

@section('customjs')
<script>
$(document).ready(function(){

  $('#defaultCheck2').on('click', function() {
    var curdate = $('#date').val().split('-');
    var newDate = curdate[2];
    var monthDay = curdate[0] + '-' + curdate[1]; 
    var year = "<?= date('Y') ?>";
    $('#date').val(monthDay + '-'+year);
  })
  
})
</script>
@endsection