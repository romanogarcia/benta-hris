@extends('layouts.master')

@section('title', 'PAG-IBIG Table Form')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header">Add new PAG-IBIG salary range</div>
    <div class="card-body">
      <form method="post" action="{{ route('pagibig.store') }}">
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
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="ee">Employee Share Percentage</label>
                <input class="form-control @error('ee') is-invalid @enderror" id="ee" type="number" placeholder="EE" name="ee">
                @error('ee')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label for="er">Employer Share Percentage</label>
                <input class="form-control @error('er') is-invalid @enderror" id="er" type="number" placeholder="ER" name="er">
                @error('er')
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