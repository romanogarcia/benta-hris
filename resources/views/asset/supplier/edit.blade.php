@extends('layouts.master')
@section('title', 'Asset Supplier')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

    <div class="card">
      <div class="card-header">
        Update Asset Supplier
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('asset_supplier.update', [$data->id]) }}">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="supplier">Supplier</label>
                <input class="form-control @error('supplier') is-invalid @enderror" type="text" id="supplier" name="supplier" value="{{ (old('supplier')) ? old('supplier'):ucfirst($data->supplier) }}" placeholder="Supplier Name">
                @error('supplier')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
                <label for="supplier">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address Name" style="height:100px">{{ (old('address')) ? old('address'):ucfirst($data->address) }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
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