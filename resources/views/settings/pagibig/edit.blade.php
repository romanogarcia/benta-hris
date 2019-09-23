@extends('layouts.master')

@section('title', 'Update PAG-IBIG Table Form')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header">Update PAG-IBIG salary range</div>
    <div class="card-body">
      <form method="post" action="{{ route('pagibig.update',[$pagibig->id]) }}">
      @csrf
      @method('PUT')
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="salary_min">Minimum Compensation Range</label>
                <input class="form-control @error('salary_min') is-invalid @enderror" id="salary_min" type="decimal" placeholder="Minimum compensation range" name="salary_min" value="{{explode('-',$pagibig->monthly_compensation)[0]}}">
                @error('salary_min')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
              <label for="salary_max">Maximum Compensation Range</label>
                <input class="form-control @error('salary_max') is-invalid @enderror" id="salary_max" type="decimal" placeholder="Maximum compensation range" name="salary_max" value="{{explode('-',$pagibig->monthly_compensation)[1]}}">
                 @error('salary_max')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="ee">Employee Share Percentage</label>
                <input class="form-control @error('ee') is-invalid @enderror" id="ee" type="number" placeholder="EE" name="ee" value="{{$pagibig->employee_share}}">
                @error('ee')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="er">Employer Share Percentage</label>
                <input class="form-control @error('er') is-invalid @enderror" id="er" type="number" placeholder="ER" name="er" value="{{$pagibig->employer_share}}">
                @error('er')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          <button class="btn btn-success float-right w-sm-100" type="submit"><i class="mdi mdi-check"></i> UPDATE</button>
		   </form>
        </div>
     
  </div>
</div>
@endsection