@extends('layouts.master')
@section('title', 'Asset Supplier')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

    <div class="card">
      <div class="card-header">
        Update Asset Supplier - {{$data->type}}
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('asset_supplier.update', [$data->id, $data->type]) }}">
            @method('PUT')
            @csrf
            <input type="hidden" name="type" value="{{$data->type}}">
            <div class="row">
              @if($data->type == "Company")
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fname">Supplier</label>
                        <input class="form-control @error('supplier') is-invalid @enderror" type="text" value="{{$data->supplier}}" id="supplier" name="supplier" placeholder="Supplier Name">
                        @error('supplier')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
              @else
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input class="form-control @error('fname') is-invalid @enderror" type="text" value="{{$data->first_name}}" id="fname" name="fname" placeholder="First Name">
                        @error('fname')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input class="form-control @error('lname') is-invalid @enderror" type="text" value="{{$data->last_name}}" id="lname" name="lname" placeholder="Last Name">
                            @error('lname')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                @endif
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input class="form-control @error('email') is-invalid @enderror" type="text" value="{{$data->email}}" id="email" name="email" placeholder="Email">
                        @error('email')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input class="form-control @error('phone') is-invalid @enderror" type="text" value="{{$data->phone}}" id="phone" name="phone" placeholder="Phone Number">
                        @error('phone')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="phone">Mobile Number</label>
                        <input class="form-control @error('mobile') is-invalid @enderror" type="text" value="{{$data->mobile}}" id="mobile" name="mobile" placeholder="Mobile Number">
                        @error('mobile')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Address</label>
                        <input class="form-control @error('address') is-invalid @enderror" type="text" value="{{$data->address}}" id="address" name="address" placeholder="Address Name">
                        @error('address')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Zip Code</label>
                        <input class="form-control @error('zipcode') is-invalid @enderror" type="text" value="{{$data->zip_code}}" id="zip_code" name="zip_code" placeholder="Zip Code Name">
                        @error('zipcode')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                  </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">City</label>
                        <input class="form-control @error('city') is-invalid @enderror" type="text" value="{{$data->city}}" id="city" name="city" placeholder="City Name">
                        @error('city')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Country</label>
                        <select class="form-control @error('country') is-invalid @enderror" type="text" value="{{old('country')}}" id="country" name="country" placeholder="Country Name">
                        <option value="">Country</option>
                        @foreach ($countries as $country)
                          <option @if($country->id == old('country') || $country->id == $data->country_id) selected @endif value="{{$country->id}}">{{ucfirst($country->country_name)}}</option>
                        @endforeach
                        </select>
                        @error('country')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                  </div>       
              </div>
                <div class="form-group">
                    <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-check"></i> UPDATE</button>
                </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('customjs')
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    });
</script>
@endsection