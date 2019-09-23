@extends('layouts.master')
@section('title', 'Asset Location')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')
    <div class="card">
      <div class="card-header">
        Update Asset Location
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('asset_location.update', [$data->id]) }}">
            @method('PUT')
            @csrf
             <div class="row">
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
                    <label for="location">Building</label>
                    <input class="form-control @error('building') is-invalid @enderror" type="text" value="{{$data->location}}" id="location" name="location" placeholder="Building Info" required>
                    @error('building')
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
                  <input class="form-control @error('zipcode') is-invalid @enderror" type="text" value="{{$data->zip_code}}" id="zipcode" name="zipcode" placeholder="Zip Code Name">
                  @error('zipcode')
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