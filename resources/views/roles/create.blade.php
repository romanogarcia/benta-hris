@extends('layouts.master')
@section('title', 'Add Role Management')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
      <div class="card-header">Add Role Management</div>
        <div class="card-body">

          <form method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="role">Role Name</label>
                    <input type="text" class="form-control form-control-sm" name="role_name" placeholder="Role Name">
                    @error('role_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                  <label for="department_id">Department</label>
                    <select class="form-control form-control-sm" name="department_id" required>
                        <option value="">-- SELECT --</option>
                        @foreach($departments as $key => $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                  <label for="page">Page</label>
                    <!-- <input id="page" type="text" class="form-control @error('page') is-invalid @enderror form-control-sm" placeholder="Page name" name="page" value="" autocomplete="off"> -->

                    <select class="form-control form-control-sm" name="page" id="" required>
                      <option value="">-- SELECT --</option>
                      @foreach($pages as $key => $page)
                          <option value="{{ $key }}">{{ strtoupper($page) }}</option>
                      @endforeach
                    </select>

                    @error('page')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
              </div>

              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                  <label for="role">Access</label>
                    <select class="form-control form-control-sm" name="role" required>
                        <option value="">-- SELECT --</option>
                        @foreach($methods as $key => $method)
                            <option value="{{ $key }}">{{ strtoupper($method) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                    <label for="is_active">Active ?</label>
                    <select class="form-control form-control-sm" name="is_active" required>
                        <option value="">-- SELECT --</option>
                        <option value="1">ACTIVE</option>
                        <option value="0">INACTIVE</option>
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
                        <button type="submit" class="btn btn-success btn-sm w-sm-100"><i class="mdi mdi-check"></i> SAVE </button> 
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