<!-- create.blade.php -->

@extends('layouts.master')
@section('title', 'Leave Request')
@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="content-wrapper">
  <div class="row">
    <div class="col-sm-12 grid-margin stretch-card">
      <div class="card">
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
            <strong>{{ $error }}</strong>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>


@endsection