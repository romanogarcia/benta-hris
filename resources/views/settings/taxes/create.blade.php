@extends('layouts.master')

@section('title', 'Tax Table Form')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header">New tax compensation range</div>
    <div class="card-body">
      <form method="post" action="{{ route('tax.store') }}">
      @csrf
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="cl">Compensation Level</label>
                <input class="form-control @error('cl') is-invalid @enderror" id="cl" type="decimal" placeholder="Minimum compensation range" name="cl">
                @error('cl')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
              <label for="Over">Over</label>
                <input class="form-control  @error('over') is-invalid @enderror" id="Over" type="decimal" placeholder="Maximum compensation range" name="over">
                @error('over')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="tax">Prescribe Minimum Witholding Tax</label>
                <input class="form-control @error('tax') is-invalid @enderror" id="tax" type="text" placeholder=" Minimum witholding Tax" name="tax">
              @error('tax')
                <div class="invalid-feedback">{{$message}}</div>
              @enderror
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="percentage">Additional Percentage over <abbr title="Compensation Level">CL</abbr></label>
                <input class="form-control @error('percentage') is-invalid @enderror" id="percentage" type="text" placeholder="Additional percentage if over CL" name="percentage">
                @error('percentage')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            </div>
          </div>
          <button class="btn btn-success float-right full-width-xs" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
		   </form>
        </div>
     
  </div>
</div>
@endsection