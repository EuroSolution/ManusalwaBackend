@extends('admin.layout')
@section('title', 'Customer Detail')
@section('page_css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<style>
    th {
        background-color: #f7f7f7;
    }

</style>
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">


            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Customer Detail</h3>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">

                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>Name</th>
                                    <td colspan="4">{{ $customer->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $customer->email ?? '' }}</td>
                                    <th>Phone</th>
                                    <td>{{ $customer->phone ?? '' }}</td>
                                </tr>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            {{-- <div class="row">
                <div class="col-md-12">
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Orders </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="myTable" class="table table-bordered table-striped">
                              <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Order No</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                </tr>
                              </thead>
                              <tbody>
                      @php $counter=1 @endphp
                                @foreach ($customer as $orders)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $orders->order_no }}</td>
                                        <td>{{ $orders->total_amount }}</td>
                                        <td>{{ ucfirst($orders->order_status) }}</td>
                                        <td>{{ date("d-M-Y",strtotime($orders->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- /.container-fluid -->
    </section>
</div>
@endsection
@section('script')
<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>
@endsection
