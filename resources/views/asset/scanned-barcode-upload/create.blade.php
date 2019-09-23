@extends('layouts.master')
@section('title', 'Upload Scanned Barcode')
@section('content')
<div class="content-wrapper">
  <div class="content">
    <div class="card">
      <div class="card-header">
        Upload Scanned Barcode
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
        <div class="row">
            <div class="col-md-3 col-lg-4">
                <form method="POST" action="{{ route('asset_scanned_barcode_upload.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Upload File <small>(.txt)</small></label>
                        <input class="form-control form-control-sm @error('file') is-invalid @enderror" type="file" id="file" name="file" accept=".txt">
                        @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="asset_location">Scanned Location <small>(location where you scanned the barcodes)</small></label>
                        <select class="form-control form-control-sm" name="asset_location" id="asset_location">
                            <option value="">Scanned Location</option>
                            @foreach($locations as $location)
                                <option value="{{$location->id}}">{{ ucfirst($location->location) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control form-control-sm" name="description" id="description" placeholder="Description (optional)" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-content-save"></i> UPLOAD</button>
                    </div>
                </form>    
            </div>
        </div>
        
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