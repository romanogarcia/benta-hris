@extends('layouts.master')
@section('title', 'Asset Inventory')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

  <div class="card">
      <div class="card-header">
        Edit Asset 
      </div>
      <div class="card-body">
        <form class="forms-sample" method="POST" action="{{route('asset_inventory.update', ['slug_token'=>$data->slug_token])}}">
        @method('PUT')
         @csrf
          <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="category"><span class="text-danger">*</span> Category</label>
                     <div class="input-group">
                        <select class="form-control @error('category') is-invalid @enderror" name="category" id="category" >
                           <option value="">-Category-</option>
                           @foreach($categories as $category)
                              <option @if($category->id == old('category') || $category->id == $data->category_id) selected @endif value="{{$category->id}}">{{ucfirst($category->category)}}</option>
                           @endforeach
                        </select>
                        @error('category')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror
                     </div>
                  </div>
               </div>
               
               <div class="col-md-6">
               <div class="form-group">
                  <label for="serial_number"><span class="text-danger">*</span>Serial Number</label>
                  <input type="text" class="form-control @error('serial_number') is-invalid @enderror" name="serial_number" id="serial_number" placeholder="Serial Number"  value="{{$data->serial_number}}">
                  @error('serial_number')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
               </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="property_number"><span class="text-danger">*</span>  Property Number</label>
                  <input type="text" class="form-control @error('property_number') is-invalid @enderror" name="property_number" id="property_number" placeholder="Property Number"  value="{{$data->property_number}}">
                  @error('property_number')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

               <div class="col-md-6">
                <div class="form-group">
                  <label for="asset_no"> Asset Number</label>
                  <input type="text" class="form-control @error('asset_no') is-invalid @enderror" name="asset_no" id="asset_no" placeholder="Asset Number"  value="{{$data->asset_number}}">
                  @error('asset_no')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>
                      
               <div class="col-md-6">
                  <div class="form-group">
                     <label for="employee"><span class="text-danger">*</span> Accountable Employee</label>
                     <div class="input-group">
                        <select style="border: 1px solid #aeaeae;" class="form-control @error('employee') is-invalid @enderror" name="employee" id="employee" >
                           <option value="">-Employee-</option>
                           @foreach($employees as $employee)
                              <option @if($employee->id == old('employee')|| $employee->id == $data->employee_id) selected @endif value="{{$employee->id}}">{{ucfirst($employee->first_name)}} {{ucfirst($employee->last_name)}}</option>
                           @endforeach
                        </select>
                        @error('employee')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror
                     </div>
                  </div>
               </div>               

               <div class="col-md-6">
                  <div class="form-group">
                     <label for="supplier"><span class="text-danger">*</span> Supplier</label>
                     <div class="input-group">
                        <select class="form-control @error('supplier') is-invalid @enderror" name="supplier" id="supplier" >
                           <option value="">-Supplier-</option>
                           @foreach($suppliers as $supplier)
                              <option @if($supplier->id == old('supplier')|| $supplier->id == $data->supplier_id) selected @endif value="{{$supplier->id}}">{{ucfirst($supplier->supplier)}}</option>
                           @endforeach
                        </select>
                        @error('supplier')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror
                     </div>
                  </div>
               </div>

               <div class="col-md-6">
                   <div class="form-group">
                      <label for="item_description"><span class="text-danger">*</span> Item Description</label>
                      <textarea name="item_description" class="form-control @error('item_description') is-invalid @enderror" id="item_description" rows="4" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; z-index: auto; position: relative; line-height: 14px; font-size: 14px; transition: none 0s ease 0s;">{{$data->item_description}}</textarea>
                      @error('item_description')
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                      @enderror
                    </div>
              	</div>

               <div class="col-md-6">
                  <div class="form-group">
                     <label for="condition">Condition</label>
                     <input type="text" class="form-control @error('condition') is-invalid @enderror" name="condition" id="condition" placeholder="Condition"  value="{{$data->condition}}">
                     @error('condition')
                        <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                        </span>
                     @enderror
                  </div>
               </div>

               <div class="col-md-6">
                <div class="form-group">
                  <label for="po_no"><span class="text-danger">*</span> PO Number</label>
                  <input type="text" class="form-control @error('po_no') is-invalid @enderror" name="po_no" id="po_no" placeholder="PO Number"  value="{{$data->purchase_order_number}}">
                  @error('po_no')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="acquisition_cost"><span class="text-danger">*</span> Acquition Cost</label>
                  <input type="text" class="form-control @error('acquisition_cost') is-invalid @enderror" name="acquisition_cost" id="acquisition_cost" placeholder="Acquisition Cost"  value="{{$data->acquisition_cost}}">
                  @error('acquisition_cost')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="date_acquired"><span class="text-danger">*</span>  Date Acquired</label>
                  <input type="date" class="form-control is_datefield @error('date_acquired') is-invalid @enderror" name="date_acquired" id="date_acquired" placeholder="Date Acquired"  value="{{$data->date_acquired}}">
                  @error('date_acquired')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="mr_number"><span class="text-danger">*</span> MR Number</label>
                  <input type="text" class="form-control @error('mr_number') is-invalid @enderror" name="mr_number" id="mr_number" placeholder="MR Number"  value="{{$data->mr_number}}">
                  @error('mr_number')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

               <div class="col-md-6">
                  <div class="form-group">
                     <label for="location"><span class="text-danger">*</span> Location</label>
                     <div class="input-group">
                        <select class="form-control @error('location') is-invalid @enderror" name="location" id="location" >
                           <option value="">-Location-</option>
                           @foreach($locations as $location)
                              <option @if($location->id == old('location')|| $location->id == $data->location_id) selected @endif value="{{$location->id}}">{{ucfirst($location->location)}}</option>
                           @endforeach
                        </select>
                        @error('location')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror
                     </div>
                  </div>
               </div>
               
              <div class="col-md-6">
                <div class="form-group">
                  <label for="warranty">Warranty</label>
                  <input type="text" class="form-control @error('warranty') is-invalid @enderror" name="warranty" id="warranty" placeholder="Warranty"  value="{{$data->warranty}}">
                  @error('warranty')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

            
          </div>

          <div class="text-right">
            <div class="form-group">
              <div class="">
                  <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-check"></i> UPDATE</button>
              </div>
            </div>
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