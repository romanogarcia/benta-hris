@extends('layouts.master')
@section('title', 'Asset Supplier')
@section('customcss')
  <style> </style>
@endsection

@section('content')
   <div class="content-wrapper">
      <div class="row">
         <div class="col-md-12 stretch-card">
            <div class="card">
               <div class="card-header">Supplier Type</div>
               <div class="card-body">
                  <form method="post"  id="supplier-form" action="{{route('asset_supplier.store')}}">
                     <div class="row">
                     @csrf
                     <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <div id="search-form_container">
                           <div class="form-group">
                              <label for="select_supplier_type">Supplier Type</label>
                              <select class="form-control" id="select_supplier_type" name="type">
                                 <option value="Company" @if(old('type')=='Company') Selected @endif>Company</option>
                                 <option value="Person" @if(old('type')=='Person') Selected @endif>Person</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                           <div id="supplier_type_container">
                           
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

@section('customjs')
   <script type="text/javascript">
      $(document).ready(function (){

         @if(old('type'))
         load_form_fields('{{old('type')}}');
         @else
         load_form_fields('Company');
         @endif
         $("#select_supplier_type").on('change', function (){
            var type       = $(this).val();
            load_form_fields(type);
         });               

         function load_form_fields(type){
            var container  = $("#supplier_type_container");
            var content    = '';
            if(type == 'Person')
               content = Person();
            if(type == 'Company')
               content = Company();

            container.html(content);
         }
         
       
         function Person(){
            return `<div class="">
                                 <div class="row">
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="First Name" name="fname" class="form-control @error('fname') is-invalid @enderror">
                                             @error('fname')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Last Name" name="lname" class="form-control @error('lname') is-invalid @enderror">
                                             @error('lname')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="E-mail" name="email" class="form-control @error('email') is-invalid @enderror">
                                             @error('email')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Phone Number" name="phone" class="form-control @error('phone') is-invalid @enderror">
                                             @error('phone')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Mobile Number" name="mobile" class="form-control @error('mobile') is-invalid @enderror">
                                             @error('mobile')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Address" name="address" class="form-control @error('address') is-invalid @enderror">
                                          @error('address')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Zip Code" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror">
                                             @error('zip_code')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>   
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="City" name="city" class="form-control @error('city') is-invalid @enderror">
                                          @error('city')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <select name="country" class="form-control">
                                             <option value="">Country</option>
                                             @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->country_name}}</option>
                                             @endforeach
                                          
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>                     
                              <div class="text-right">
                                 <div class="form-group">
                                 <div class="">
                                       <button class="btn btn-success btn-save float-right"  type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
                                 </div>
                                 </div>
                              </div>
                           `;
         }

         function Company(){
            return `<div class="">
                                 <div class="row">
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Supplier Name" name="supplier" class="form-control @error('supplier') is-invalid @enderror">
                                             @error('supplier')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="E-mail" name="email" class="form-control @error('email') is-invalid @enderror">
                                             @error('email')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Phone Number" name="phone" class="form-control @error('phone') is-invalid @enderror">
                                             @error('phone')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Mobile Number" name="mobile" class="form-control @error('mobile') is-invalid @enderror">
                                             @error('mobile')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Address" name="address" class="form-control @error('address') is-invalid @enderror">
                                          @error('address')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="Zip Code" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror">
                                             @error('zip_code')
                                             <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                             </span>
                                             @enderror
                                       </div>
                                    </div>   
                                    <div class="col-4">
                                       <div class="form-group">
                                          <input type="text" placeholder="City" name="city" class="form-control @error('city') is-invalid @enderror">
                                          @error('city')
                                          <span class="invalid-feedback" role="alert">
                                             <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror
                                       </div>
                                    </div>
                                    <div class="col-4">
                                       <div class="form-group">
                                          <select name="country" class="form-control">
                                             <option value="">Country</option>
                                             @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->country_name}}</option>
                                             @endforeach
                                          
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>                     
                              <div class="text-right">
                                 <div class="form-group">
                                 <div class="">
                                       <button class="btn btn-success btn-save float-right"  type="submit"><i class="mdi mdi-content-save"></i> SAVE</button>
                                 </div>
                                 </div>
                              </div>
                           `;
         }
      });
   </script>
@endsection