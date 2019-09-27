@extends('layouts.master')
@section('title', 'Asset Inventory')
@section('content')
<div class="content-wrapper">
  <div class="content">
  @include('includes.messages')

  <!--
  property_number	
  item_description	
  asset_number	
  serial_number	
  mr_number	
  purchase_order_number	
  acquisition_cost	
  date_acquired	
  condition	
  warranty	
  slug_token	
  added_by	
  employee_id	
  location_id	
  supplier_id	
  category_id-->

    <div class="card">
      <div class="card-header">
        Add Asset 
      </div>
      <div class="card-body">
        <form class="forms-sample" method="POST" action="{{route('asset_inventory.store')}}">
         @csrf
          <div class="row">

               <div class="col-md-6">
                     <div class="form-group">
                       <label for="asset_no"> Asset Number</label>
                       <input type="text" class="form-control @error('asset_no') is-invalid @enderror" name="asset_no" id="asset_no" placeholder="Asset Number"  value="{{old('asset_no')}}">
                       @error('asset_no')
                          <span class="invalid-feedback" role="alert">
                             <strong>{{ $message }}</strong>
                          </span>
                       @enderror
                     </div>
                   </div>

              <div class="col-md-6">
                  <div class="form-group">
                    <label for="date_acquired">Date Acquired</label>
                    <input type="date" class="form-control @error('date_acquired') is-invalid @enderror is_datefield" name="date_acquired" id="date_acquired" placeholder="Date Acquired"  value="{{$date}}">
                    @error('date_acquired')
                       <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                       </span>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                     <div class="form-group">
                        <label for="category">Category</label>
                        <div class="input-group">
                           <select class="form-control @error('category') is-invalid @enderror" name="category" id="category" >
                              <option value="">-Select Category-</option>
                              @foreach($categories as $category)
                                 <option @if($category->id == old('category')) selected @endif value="{{$category->id}}">{{ucfirst($category->category)}}</option>
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
                        <label for="item_description">Item Description</label>
                        <textarea name="item_description" class="form-control @error('item_description') is-invalid @enderror" id="item_description" rows="4" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; z-index: auto; position: relative; line-height: 14px; font-size: 14px; transition: none 0s ease 0s;"></textarea>
                        @error('item_description')
                           <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                           </span>
                        @enderror
                      </div>
                   </div>

                   <div class="col-md-6">
                        <div class="form-group">
                           <label for="employee">Accountable Employee</label>
                           <div class="input-group">
                              <select style="border: 1px solid #aeaeae;" class="form-control @error('employee') is-invalid @enderror" name="employee" id="employee" >
                                 <option value="">-Select Employee-</option>
                                 @foreach($employees as $employee)
                                    <option @if($employee->id == old('employee')) selected @endif value="{{$employee->id}}">{{ucfirst($employee->first_name)}} {{ucfirst($employee->last_name)}}</option>
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
                           <label for="supplier">Supplier</label>
                           <div class="input-group">
                              <select class="form-control @error('supplier') is-invalid @enderror" name="supplier" id="supplier" >
                                 <option value="">-Select Supplier-</option>
                                 @foreach($suppliers as $supplier)
                                    @if($supplier->type=="Person")
                                    <option @if($supplier->id == old('supplier')) selected @endif value="{{$supplier->id}}">{{ucfirst($supplier->first_name." ".ucfirst($supplier->last_name))}}</option>
                                    @else
                                    <option @if($supplier->id == old('supplier')) selected @endif value="{{$supplier->id}}">{{ucfirst($supplier->supplier)}}</option>
                                    @endif
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

                     <div class="col-md-12">
                           <div class="form-group">
                              <label for="location">Location</label>
                              <div class="input-group">
                                 <select class="form-control @error('location') is-invalid @enderror" name="location" id="location" >
                                    <option value="">-Select Location-</option>
                                    @foreach($locations as $location)
                                       <option @if($location->id == old('location')) selected @endif value="{{$location->id}}">
                                       
                                             @php
                                             $response= $location->location;
                                             if(!empty($location->address))
                                                $response.=", ".$location->address;
                                             if(!empty($location->city))
                                                $response.=", ".$location->city;
                                             if(!empty($location->country_name))
                                                $response.=", ".$location->country_name;
                                             if(!empty($location->zip_code))
                                                $response.=", ".$location->zip_code;
                                          @endphp
                                                {{$response}} 
                                       
                                       </option>
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
                             <label for="acquisition_cost">Acquition Cost</label>
                             <input type="text" class="form-control @error('acquisition_cost') is-invalid @enderror" name="acquisition_cost" id="acquisition_cost" placeholder="Acquisition Cost"  value="{{old('acquisition_cost')}}">
                             @error('acquisition_cost')
                                <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                                </span>
                             @enderror
                           </div>
                         </div>

                         <div class="col-md-6">
                              <div class="form-group">
                                <label for="warranty">Warranty</label>
                                <input type="text" class="form-control @error('warranty') is-invalid @enderror" name="warranty" id="warranty" placeholder="Warranty"  value="{{old('warranty')}}">
                                @error('warranty')
                                   <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                   </span>
                                @enderror
                              </div>
                            </div>

                            <div class="col-md-6">
                                 <div class="form-group">
                                    <label for="category">Condition</label>
                                    <div class="input-group">
                                       <select class="form-control @error('condition') is-invalid @enderror" name="condition" id="condition">
                                          <option value="">-Select Condition-</option>
                                          <option value="New">New</option>
                                          <option value="Used">Used</option>
                                          <option value="Ancient">Ancient</option>
                                          <option value="Defect">Defect</option>
                                       </select>
                                       @error('condition')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                       @enderror
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="condition">Notes</label>
                                       <input type="text" class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" placeholder="Notes"  value="{{old('notes')}}">
                                       @error('notes')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                       @enderror
                                    </div>
                                 </div>
      
               <div class="col-md-6">
               <div class="form-group">
                  <label for="serial_number">Serial Number</label>
                  <input type="text" class="form-control @error('serial_number') is-invalid @enderror" name="serial_number" id="serial_number" placeholder="Serial Number"  value="{{old('serial_number')}}">
                  @error('serial_number')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
               </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="property_number">Property Number</label>
                  <input type="text" class="form-control @error('property_number') is-invalid @enderror" name="property_number" id="property_number" placeholder="Property Number"  value="{{old('property_number')}}">
                  @error('property_number')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>

               <div class="col-md-6">
                <div class="form-group">
                  <label for="po_no">PO Number</label>
                  <input type="text" class="form-control @error('po_no') is-invalid @enderror" name="po_no" id="po_no" placeholder="PO Number"  value="{{old('po_no')}}">
                  @error('po_no')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                     </span>
                  @enderror
                </div>
              </div>
              
            

              <div class="col-md-6">
                <div class="form-group">
                  <label for="mr_number">MR Number</label>
                  <input type="text" class="form-control @error('mr_number') is-invalid @enderror" name="mr_number" id="mr_number" placeholder="MR Number"  value="{{old('mr_number')}}">
                  @error('mr_number')
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
                  <button class="btn btn-success float-right" type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
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