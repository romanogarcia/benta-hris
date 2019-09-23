@extends('layouts.master')

@section('title', 'PhilHealth Table Form')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header">Add new philhealth salary range</div>
    <div class="card-body">
      <form method="post" action="{{ route('philhealth.store') }}">
      @csrf
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="salary_min">Minimum Compensation Range</label>
                <input class="form-control @error('salary_min') is-invalid @enderror" id="salary_min" type="decimal" placeholder="Minimum compensation range" name="salary_min">
                @error('salary_min')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="salary_max">Maximum Compensation Range</label>
                <input class="form-control @error('salary_max') is-invalid @enderror" id="salary_max" type="decimal" placeholder="Maximum compensation range" name="salary_max">
                @error('salary_max')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label for="monthly">Monthly Premium</label>
                <input class="form-control @error('monthly') is-invalid @enderror" id="monthly" type="number" placeholder="Monthly Premium" name="monthly">
                @error('monthly')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          <button class="btn btn-success float-right w-sm-100" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
		  </form>
        </div>
  </div>
</div>
@endsection