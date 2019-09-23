@extends('layouts.master')
@section('title', 'Asset Category')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

    <div class="card">
      <div class="card-header">
        Add Asset Category
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('asset_category.store') }}">
            @csrf
            <div class="form-group">
                <label for="category">Category</label>
                <input class="form-control @error('category') is-invalid @enderror" type="text" value="{{old('category')}}" id="category" name="category" placeholder="Category Name">
                @error('category')
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