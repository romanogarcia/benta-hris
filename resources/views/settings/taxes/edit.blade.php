@extends('layouts.master')

@section('title', 'Update Tax')

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header">Update tax compensation range</div>
    <div class="card-body">
      <form method="post" action="{{ route('tax.update', [$tax->id]) }}">
      @method('PUT')
      @csrf
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="cl">Compensation Level</label>
                <input class="form-control" id="cl" type="decimal" placeholder="minimum compensation range" name="cl" value="{{$tax->compensation_level}}">
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
              <label for="Over">Over</label>
                <input class="form-control" id="Over" type="decimal" placeholder="maximum compensation range" name="over" value="{{$tax->over}}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="tax">Prescribe Minimum Witholding Tax</label>
                <input class="form-control @error('tax') is-invalid @enderror" id="tax" type="text" placeholder=" Minimum Witholding Tax" name="tax" value="{{$tax->tax}}">
              @error('tax')
                <div class="invalid-feedback">{{$message}}</div>
              @enderror
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="form-group">
                <label for="percentage">Additional Percentage over <abbr title="Compensation Level">CL</abbr></label>
                <input class="form-control" id="percentage" type="text" placeholder="Additional percentage if over CL" name="percentage" value="{{$tax->percentage}}">
            </div>
            </div>
          </div>
           <button class="btn btn-success float-right full-width-xs" type="submit"> <i class="mdi mdi-check"></i> UPDATE</button>
		   </form>
        </div>
  </div>
</div>
@endsection