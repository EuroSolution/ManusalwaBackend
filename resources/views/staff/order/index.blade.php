@extends('staff.layout')
@section('title', 'Orders')
@section('css')
    <!-- Datatables -->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <style>
        .addBtn{
            float: right;
            /*margin-top: 10px;*/
        }
        td{
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper" style="margin: auto;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/staff/dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <!-- /.card -->

                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div style="text-align: center;">
                                    <a href="{{url('staff/orders')}}"><button style="background-color: #a69e22;color: white;" id="completed">All</button></a>
                                    <a href="{{url('staff/orders')}}?status=processing"><button style="background-color: #007bff;color: white;" id="processing">Processing</button></a>
                                    <a href="{{url('staff/orders')}}?status=pending"><button style="background-color: #6c757d;color: white;" id="panding">Pending</button></a>
                                    <a href="{{url('staff/orders')}}?status=completed"><button style="background-color: #28a745;color: white;" id="completed">Completed</button></a>
                                    <a href="{{url('staff/orders')}}?status=cancelled"><button style="background-color: #dc3545;color: white;" id="cancelled">Cancelled</button></a>
                                    <a href="{{url('staff/orders')}}?status=delivered"><button style="background-color: #17a2b8;color: white;" id="delivered">Delivered</button></a>
                                </div>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Order No</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Coupon</th>
                                        <th>Status</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <div id="confirmModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"  style="background-color: #343a40;
            color: #fff;">
                        <h2 class="modal-title">Confirmation</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 align="center" style="margin: 0;">Are you sure you want to delete this ?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="ok_delete" name="ok_delete" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('admin/datatables/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script>
        var DataTable = $("#example1").DataTable({
                dom: "Blfrtip",
                buttons: [{
                    extend: "copy",
                    className: "btn-sm"
                }, {
                    extend: "csv",
                    className: "btn-sm"
                }, {
                    extend: "excel",
                    className: "btn-sm"
                }, {
                    extend: "pdfHtml5",
                    className: "btn-sm"
                }, {
                    extend: "print",
                    className: "btn-sm"
                }],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: `{{route('staff.staffOrders')}}`+"?status={{$request->get('status')}}",
                },
                order: [ [0, 'asc'] ],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    {data: 'order_number', name: 'order_number'},
                    {data: 'user', name: 'user'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'coupon', name: 'coupon'},
                    {data: 'status', name: 'status'},
                    {data: 'order_date', name: 'order_date'},
                    {data: 'action', name: 'action', orderable: false}
                ]

            });
        $(document).ready(function () {
            DataTable.ajax.reload();

            var delete_id;
            $(document,this).on('click','.delete',function(){
                delete_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });

            $(document).on('click','#ok_delete',function(){
                $.ajax({
                    type:"delete",
                    url:`{{url(request()->segment(1).'/destroy')}}/${delete_id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function(){
                        $('#ok_delete').text('Deleting...');
                        $('#ok_delete').attr("disabled",true);
                    },
                    success: function (data) {
                        DataTable.ajax.reload();
                        $('#ok_delete').text('Delete');
                        $('#ok_delete').attr("disabled",false);
                        $('#confirmModal').modal('hide');
                        if(data === false) {
                            toastr.error('Something Went Wrong..!!');
                        }else{
                            toastr.success('Record Delete Successfully');
                        }
                    }
                })
            });
        })

        $('#processing, #panding, #completed').click(function(e){
            var targeted_tab = $(this).attr('id');

            /* $.ajax({
                    type:"GET",
                    url:`{{url('staff/orders')}}?targetTab=targeted_tab`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        DataTable.ajax.reload();

                    }
                }) */

        });
    </script>
@endsection
