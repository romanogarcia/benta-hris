@extends('layouts.master')
@section('title', 'Barcode Entry')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

    <div class="card">
      <div class="card-header">
        Barcode Entry
      </div>
      <div class="card-body">
        
        <div class="row">
            <div class="col-md-3 col-lg-4">
                <form method="POST" action="{{ route('asset_scanned_barcode.store') }}" id="form-barcode_entry">
                    @csrf
                    <div class="form-group">
                        <select class="form-control form-control-sm" name="asset_location" id="asset_location">
                            <option value="">Your Location</option>
                            @foreach($locations as $location)
                                <option value="{{$location->id}}">{{ ucfirst($location->location) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-sm" type="text" id="barcode" name="barcode" placeholder="Scan Barcode..." autofocus="true">
                    </div>
                    <div class="form-group" style="display: none;">
                        <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
                    </div>
                </form>    
            </div>
            <div class="col-md-9 col-lg-8">
                <div id="scanned_asset_container"></div>
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



        $("#form-barcode_entry").on('submit', function (e){
            e.preventDefault();
            var f       = $(this);
            var input   = f.find('input');
            var select  = f.find('select');

            input.attr('readonly', 'true');
            select.attr('readonly', 'true');

            $.ajax({
                type    : f.attr('method'),
                url     : f.attr('action'),
                data    : f.serialize(),
                dataType: 'json',
                success : function (response){
                    
                    for(var key in response.errors){
                        var message     = response.errors[key];
                        var id          = $("#"+key);
                        
                        id.removeClass('is-invalid');
                        id.next('.invalid-feedback').remove();
                        id.removeClass('is-valid');
                        id.next('.valid-feedback').remove();

                        if(!message){
                            id.addClass('is-valid');
                            id.after('<div class="valid-feedback">'+message+'</div>');
                        }else{
                            id.addClass('is-invalid');
                            id.after('<div class="invalid-feedback">'+message+'</div>');
                        }
                    }

                    if(response.success)
                        $('#barcode').val('');
                        

                    input.removeAttr('readonly', 'true');
                    select.removeAttr('readonly', 'true');
                },
            });
        });


    });
</script>
@endsection