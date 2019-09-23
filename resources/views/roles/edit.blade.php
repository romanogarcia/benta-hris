@extends('layouts.master')
@section('title', 'Edit Role Management')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">Edit Role</div>
        <div class="card-body">


          <form method="POST" action="{{ route('roles.update', $role->id ) }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="department_id">Select Department</label>
                    <select class="form-control form-control-sm @error('department_id') is-invalid @enderror" name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $key => $department)
                            <option value="{{ $department->id }}" {{ $role->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="page">Select Page</label>
                    <select class="form-control form-control-sm @error('page') is-invalid @enderror" name="page">
                        <option value="">Select Page</option>
                        @foreach($pages as $key => $page)
                            <option value="{{ $key }}" {{ $role->page == $key ? 'selected' : '' }}>{{ strtoupper($page) }}</option>
                        @endforeach
                    </select>
                    @error('page')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="role">Select Access</label>
                    <select class="form-control form-control-sm @error('role') is-invalid @enderror" name="role" >
                        <option value="">Select Access</option>
                        @foreach($methods as $key => $method)
                            <option value="{{ $key }}" {{ $role->role == $key ? 'selected' : '' }}>{{ strtoupper($method) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                    <label for="is_active">Select Status</label>
                    <select class="form-control form-control-sm @error('is_active') is-invalid @enderror" name="is_active" >
                        <option value="">Select Status</option>
                        <option value="1" {{ $role->is_active == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $role->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

            </div><!-- /row -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success btn-sm w-sm-100"><i class="mdi mdi-check"></i> UPDATE </button> 
                    </div>
                </div>
            </div>


          </form>    
        </div>
      </div>
    </div>
  </div> 
</div>
@endsection