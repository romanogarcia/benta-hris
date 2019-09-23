@extends('layouts.master')
@section('title', 'Add page and permission')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
      <div class="card-header">Add Page And Permissions</div>
        <div class="card-body">

          <form method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="role">Page Name</label>
                    <select class="form-control form-control-sm" name="parent_id" required>
                        <option value="">-- SELECT PARENT MODULE--</option>
						@foreach($pages as $page)
						<option value="{{$page}}">{{$page}}</option>
						@endforeach
                    </select>
                    @error('parent_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="form-group">
                  <label for="parent_id">Permissions</label>
                    <select class="form-control form-control-sm" name="parent_id" required>
                        <option value="">-- SELECT PARENT MODULE--</option>
                    </select>
                    @error('parent_id')
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