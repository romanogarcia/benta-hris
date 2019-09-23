@extends('layouts.master')
@section('title', 'Company')
@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid py-5">
            <div class="d-flex">
              <div class="mr-auto p-2">
                <a class="btn btn-primary mb-3" href="{{ route ('company.create') }}"><i class="fas fa-pencil-square-o"></i> ADD NEW COMPANY</a>  
              </div>
            </div>
            <div class="card">
                <div class="card-header">Company Information</div>
                <div class="card-body table-responsive">
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
                    <table class="table" id="data_table">
                        <thead>
                            <th>Date:</th>
                            <th>Company Name:</th>
                            <th>Email:</th>
                            <th>Phone:</th>
                            <th>Website:</th>
                            <th>Address:</th>
                            <th>Zip Code:</th>
                            <th>City:</th>
                            <th>Country:</th>
                            <th>Extra Address:</th>
                            <th>Business Number:</th>
                            <th>Tax Number:</th>
                            <th colspan="2">Action:</th>
                        </thead>
                        <tbody>
                        @foreach($record as $r)
                        <tr>
                            <td>{{ date("Y-m-d", strtotime($r->created_at)) }}</td>
                            <td>{{ ucfirst($r->company_name) }}</td>
                            <td>
                                @if(!empty($r->email))
                                    {{ $r->email }}
                                @endif  
                            </td>
                            <td>
                                @if(!empty($r->phone))
                                    {{ $r->phone }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($r->website))
                                    <a href="@if(strpos($r->website,'https://') === false && strpos($r->website,'http://') === false) https://{{ $r->website }} @else {{ $r->website }} @endif" target="_blank">{{ $r->website }}</a>
                                @endif
                            </td>
                            <td>
                                {{ $r->address }}
                            </td>
                            <td>
                                @if(!empty($r->zip_code))
                                    {{ $r->zip_code }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($r->city))
                                    {{ $r->city }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($r->country))
                                    {{ $r->country }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($r->extra_address))
                                    {{ $r->extra_address }}
                                @endif
                            </td>
                            <td>{{ $r->business_number }}</td>
                            <td>{{ $r->tax_number }}</td>
                            <td>
                            <div class="row col-lg">
                                <div class="col">
                                <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon btn-sm" onclick="window.location.href = '{{ route('company.edit', [$r->id]) }}';"><i class="mdi mdi-lead-pencil"></i></button>
                                </div>
                                
                            </div>
                            </td>
                            <td>
                            <div class="row col-lg">
                                <div class="col">
                                    <button type="submit" class='btn btn-outline-secondary btn-rounded btn-icon' data-toggle="modal" data-target="#modal-delete-{{$r->id}}"><i class="mdi mdi-delete"></i></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-delete-{{$r->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalCenterTitle">Are you sure?</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Delete record <span class="badge badge-primary">{{ $r->company_name }}</span> ? <br>This can't be undone.
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                  <form action="{{ route('company.destroy', [$r->id]) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                  <button type="submit" class="btn btn-danger">Delete</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                        @endforeach
                        </tbody>
                    </table>
                    {{$record->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

@endsection

@section('javascript')

<!-- OPTIONAL SCRIPTS -->
<script src="/dist/plugins/chart.js/Chart.min.js"></script>
<script src="/dist/js/demo.js"></script>
<script src="/dist/js/pages/dashboard3.js"></script>


<!-- DataTables -->
<script src="/dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
  $(document).ready(function(){

  })
</script>
@endsection