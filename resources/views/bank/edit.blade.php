@extends('layouts.master')
@section('title', 'Company Bank')
@section('content')
<div class="content-wrapper">

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">Bank Information</div>
        <div class="card-body">
          <form method="POST" action="{{ route('banks.update', $bank->id) }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PATCH')
            
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <label for="bank_name">Bank Name</label>
                    <input id="bank_name" type="text" class="form-control @error('bank_name') is-invalid @enderror form-control-sm" placeholder="Last Name" name="bank_name" value="{{ $bank->bank_name }}" autocomplete off required>
                    @error('bank_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <label for="address">Address</label>
                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror form-control-sm" placeholder="Address" name="address" value="{{ $bank->address }}" autocomplete="off">
                      @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
              </div>

              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <label for="extra_address">Extra Address</label>
                    <input id="extra_address" type="text" class="form-control @error('extra_address') is-invalid @enderror form-control-sm" placeholder="Extra Address" name="extra_address" value="{{ $bank->extra_address }}" autocomplete="off">
                    @error('extra_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->


            <div class="row">
              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="city">City</label>
                    <input id="city" type="text" class="form-control @error('city') is-invalid @enderror form-control-sm" placeholder="City" name="city" value="{{ $bank->city }}" autocomplete="off">
                      @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="state">State / Province</label>
                    <input id="state" type="text" class="form-control @error('state') is-invalid @enderror form-control-sm" placeholder="State / Province" name="state" value="{{ $bank->state }}" autocomplete="off">
                    @error('state')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="zipcode">Zip Code</label>
                    <input id="zipcode" type="text" class="form-control @error('zipcode') is-invalid @enderror form-control-sm" placeholder="Zip Code" name="zipcode" value="{{ $bank->zipcode }}" autocomplete="off">
                    @error('zipcode')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->


            <div class="row">
              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="country">Country</label>
                    <input id="country" type="text" class="form-control @error('country') is-invalid @enderror form-control-sm" placeholder="Country" name="country" value="{{ $bank->country }}" autocomplete="off">
                      @error('country')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="iban">IBAN</label>
                    <input id="iban" type="text" class="form-control @error('iban') is-invalid @enderror form-control-sm" placeholder="IBAN" name="iban" value="{{ $bank->IBAN }}" autocomplete="off">
                    @error('iban')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="bic">BIC</label>
                    <input id="bic" type="text" class="form-control @error('bic') is-invalid @enderror form-control-sm" placeholder="BIC" name="bic" value="{{ $bank->BIC }}" autocomplete="off">
                    @error('bic')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="member_no">Member No</label>
                    <input id="member_no" type="text" class="form-control @error('member_no') is-invalid @enderror form-control-sm" placeholder="Member No" name="member_no" value="{{ $bank->member_no }}" autocomplete="off">
                      @error('member_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
              </div>

              <div class="col-md-4 col-sm-12">
                <div class="form-group">
                  <label for="clearing_no">Clearing No</label>
                    <input id="clearing_no" type="text" class="form-control @error('clearing_no') is-invalid @enderror form-control-sm" placeholder="clearing_no No" name="clearing_no" value="{{ $bank->clearing_no }}" autocomplete="off">
                    @error('clearing_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>

            </div><!-- /row -->
            
            <br>
            <br>

            <div class="row">
              <div class="col-md-3 offset-md-4">
                <div class="row">
                  <div class="col">
                    <a href="{{ route('banks.index') }}" class="btn btn-danger btn-sm btn-block"><i class="mdi mdi-backspace p-2"></i> CANCEL </a> 
                  </div>
                  <div class="col">
                    <button type="submit" class="btn btn-success btn-sm btn-block"><i class="mdi mdi-account-plus p-2"></i> SUBMIT </button> 
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