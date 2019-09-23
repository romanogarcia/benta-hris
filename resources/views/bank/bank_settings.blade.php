@extends('layouts.master')
@section('title', 'Settings')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" style="margin:auto;">Settings</div>
			</div>	
		  </div>
        <div class="card-body">
			
        @if ($errors->any())
          <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <i class="mdi mdi-alert-circle"></i>
            <strong>{{ $error }}</strong>
            @endforeach
          </div>
          @endif
          @if ($message = Session::get('success')) 
          <div class="alert alert-success" role="alert">
            <i class="mdi mdi-alert-circle"></i>
            <strong>{{ $message }}</strong>
          </div>
          @endif
          <form method="POST" action="{{ route('banks.update_settings') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="row">
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="form-group">
                <label for="company_logo"><span class="text-danger"></span> Upload logo </label>
                  <input type="file" id="input-file-now" class="dropify  @error('company_logo') is-invalid @enderror" name="company_logo" value="{{ old('company_logo') }}" accept='image/*'/>
                 
                </div>
				     
              </div>

              <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="form-group">
					   <label for="company_mobile_logo"><span class="text-danger"></span> Upload mobile logo </label>
                  		<input type="file"  class="dropify1  @error('company_mobile_logo') is-invalid @enderror" name="company_mobile_logo" value="{{ old('company_mobile_logo') }}" accept='image/*'/>
					   
                  </div>
				 
                
              </div>

               <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="form-group">
					   <label for="browser_icon"><span class="text-danger"></span> Upload Browser Icon </label>
                  		<input type="file"  class="dropify2  @error('browser_icon') is-invalid @enderror" name="browser_icon" value="{{ old('browser_icon') }}" accept='image/*'/>
					   
                  </div>
              </div>
            </div>
            <div class="row">
				<input type="hidden" name="company_id" id="company_id" value="{{ $r->id }}" />
              <div class="col-md-12 col-sm-12">
                  <div class="form-group">
					<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12">	  
					  <label for="date_format">Date Format</label>
						  
						  <select id="date_format" class="form-control @error('date_format') is-invalid @enderror form-control-sm" name="date_format">
							<option value="">Select Date Format</option>
							@foreach($date_format as $df_key => $df_value)
							  <option {{($df_key == $r->date_format)?'selected':''}} value="{{$df_key}}">{{$df_value}}</option>
							@endforeach
						  </select>
						  @error('date_format')
							  <span class="invalid-feedback" role="alert">
								  <strong>{{ $message }}</strong>
							  </span>
						  @enderror
					</div>	
					</div>  
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
						<input id="payroll_deactivate" type="checkbox" name="payroll_deactivate" value="1" autocomplete="off" {{ (Auth::user()->payroll_deactivate)?"checked":"" }}>
						Deactivate Payroll 
					  <i class="input-helper"></i></label>
                </div>
              </div>
			</div>
			 <div class="row"> 
              <div class="col-md-12 col-sm-12">
				   <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
						<input  id="time_in_out_deactivate" type="checkbox"  name="time_in_out_deactivate" value="1" autocomplete="off" {{ (Auth::user()->time_in_out_deactivate)?"checked":"" }} >
						Deactivate Time-In and Time-Out 
					  <i class="input-helper"></i></label>
                </div>
               
              </div>
            </div><!-- /row -->
			<div class="row"> 
              <div class="col-md-12 col-sm-12">
				   <div class="form-check form-check-flat form-check-primary">
                      <label class="form-check-label">
						<input  id="nx_login_failed" type="checkbox"  name="nx_login_failed" value="1" autocomplete="off" {{ ($r->nx_login_failed)?"checked":"" }} >
						Activate 3x Login User Failure
					  <i class="input-helper"></i></label>
                </div>
               
              </div>
            </div>
			  
            <br>
            <br>

            <div class="row">
              <div class="col-md-12">
               <div class="text-right">
					<button type="submit" class="btn btn-success"><i class="mdi mdi-check"></i>&nbsp;UPDATE</button>
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



@section('customjs')

<script type="text/javascript">
$(document).ready(function(){

  $('.dropify').attr("data-default-file", "{{ asset('images/' . $r->company_logo) }}");
  // Basic
  $('.dropify').dropify();
  $('.dropify1').attr("data-default-file", "{{ asset('images/' . $r->company_mobile_logo) }}");
  // Basic
  $('.dropify1').dropify();
  $('.dropify2').attr("data-default-file", "{{ asset('images/' . $r->browser_icon) }}");	
  $('.dropify2').dropify();
});   
</script>
@endsection