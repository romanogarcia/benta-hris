@extends('layouts.master')
@section('title', 'Asset Supplier')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

    <div class="card">
      <div class="card-header">
        Add Asset Supplier
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('asset_supplier.store') }}">
            @csrf
            <div class="form-group">
                <label for="supplier">Supplier</label>
                <input class="form-control @error('supplier') is-invalid @enderror" type="text" value="{{old('supplier')}}" id="supplier" name="supplier" placeholder="Supplier Name">
                @error('supplier')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <label for="supplier">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address Name" style="height:100px">{{old('address')}}</textarea>
                @error('address')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
            <div class="form-group">
                <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
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