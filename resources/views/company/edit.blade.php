@extends('layouts.master')
@section('title', 'Company')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
          <div class="card-header">
              Company Information
          </div>
        <div class="card-body">
        @if ($message = Session::get('success'))
              <div class="alert alert-success" role="alert">
                  <i class="mdi mdi-alert-circle"></i>
                  <strong>{{ $message }}</strong>
              </div>
        @elseif($message = Session::get('error'))
            <div class="alert alert-danger" role="alert">
                <i class="mdi mdi-alert-circle"></i>
                <strong>{{ $message }}</strong>
            </div>
        @endif

          <form method="POST" action="{{ route('company.update', [$r->id]) }}" enctype="multipart/form-data" autocomplete="off">
            @method('PUT')
            @csrf
			                      
            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="company_name"><span class="text-danger">*</span> Company Name</label>
                    <input id="company_name" value="{{$r->company_name}}" type="text" class="form-control @error('company_name') is-invalid @enderror form-control-sm" placeholder="Company Name" name="company_name" value="{{ old('company_name') }}" autocomplete off>
                    @error('company_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email"><span class="text-danger">*</span> Email</label>
                    <input id="email" type="text" value="{{$r->email}}" class="form-control @error('email') is-invalid @enderror form-control-sm" placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" value="{{$r->phone}}" class="form-control @error('phone') is-invalid @enderror form-control-sm" placeholder="Phone" name="phone" value="{{ old('phone') }}" autocomplete="off">
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>


                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input id="city" type="text" value="{{$r->city}}" class="form-control @error('city') is-invalid @enderror form-control-sm" placeholder="City" name="city" value="{{ old('city') }}" autocomplete="off">
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <input id="zip_code" type="text" value="{{$r->zip_code}}" class="form-control @error('zip_code') is-invalid @enderror form-control-sm" placeholder="Zip Code" name="zip_code" value="{{ old('zip_code') }}" autocomplete="off">
                        @error('zip_code')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                    <label for="country_id">Country</label>
                        <select name="country_id" id="country_id" class="form-control @error('country_id') is-invalid @enderror form-control-sm" >
                          <option value="">Select Country</option>
                          @foreach($countries as $country)
                            <option value="{{$country->id}}" {{($r->country_id == $country->id) ? 'selected':''}}>{{ucfirst($country->country_name)}}</option>
                          @endforeach 
                        </select>
                        @error('country_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                    <label for="address"><span class="text-danger">*</span> Address</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror form-control-sm"  placeholder="Address" name="address" autocomplete="off" rows="3">{{$r->address}}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="extra_address">Extra Address</label>
                        <textarea id="extra_address" class="form-control @error('extra_address') is-invalid @enderror form-control-sm"  placeholder="Extra Address" name="extra_address" value="{{ old('extra_address') }}" autocomplete="off" rows="4">{{$r->extra_address}}</textarea>
                        @error('extra_address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
              
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <label for="website">Website</label>
                    <input id="website" type="text" value="{{$r->website}}" class="form-control @error('website') is-invalid @enderror form-control-sm" placeholder="Website" name="website" value="{{ old('website') }}" autocomplete="off">
                    @error('website')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
              </div>
              <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                        <label for="business_number">Business Number</label>
                        <input id="business_number" type="text" value="{{$r->business_number}}" class="form-control @error('business_number') is-invalid @enderror form-control-sm" placeholder="Business Number" name="business_number" value="{{ old('business_number') }}" autocomplete="off">
                        @error('business_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="form-group">
                    <label for="tax_number">Tax Number</label>
                        <input id="tax_number" type="text" value="{{$r->tax_number}}" class="form-control @error('tax_number') is-invalid @enderror form-control-sm" placeholder="Tax Number" name="tax_number" value="{{ old('tax_number') }}" autocomplete="off">
                        @error('tax_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
           
            <div class="row">
              <div class="col-md-3 offset-md-4">
                <div class="row">
                  <!-- <div class="col">
                    <a href="{{ route('company.index') }}"  class="btn btn-danger btn-sm btn-block"><i class="mdi mdi-backspace p-2"></i> CANCEL </a> 
                  </div> -->
                  <div class="col btn-form-container">
                    <button type="button" class="btn btn-primary btn-sm btn-block" id="btn-edit"><i class="mdi mdi-pencil p-2"></i> EDIT </button> 
                    <button style="display:none;" type="submit" class="btn btn-success btn-sm btn-block" id="btn-save"><i class="mdi mdi-check p-2"></i> SAVE </button> 
                  </div>
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

  // Used events
  var drEvent = $('#input-file-events').dropify();

  drEvent.on('dropify.beforeClear', function(event, element){
      return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
  });

  drEvent.on('dropify.afterClear', function(event, element){
      alert('File deleted');
  });

  drEvent.on('dropify.errors', function(event, element){
      console.log('Has Errors');
  });

  var drDestroy = $('#input-file-to-destroy').dropify();
  drDestroy = drDestroy.data('dropify')
  $('#toggleDropify').on('click', function(e){
      e.preventDefault();
      if (drDestroy.isDropified()) {
          drDestroy.destroy();
      } else {
          drDestroy.init();
      }
  })
});
</script>



<script type="text/javascript">
  $(document).ready(function (){
    editable();
    function not_editable(){
      $('input').attr('readonly',true);
      $('textarea').attr('readonly',true);
      $('#btn-save').hide();
      $('#btn-edit').show();
    }

    function editable(){
      $('input').removeAttr('readonly',true);
      $('textarea').removeAttr('readonly',true);
      $('#btn-save').show();
      $('#btn-edit').hide();
    }

    $('#btn-edit').on('click', function (){
      editable();
    });

  });
</script>
@endsection
