@extends('staff.layout')
@section('title', 'Order Details')
@section('content')
    <div class="content-wrapper" style="margin: auto;">
        <section class="content-header">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Order Details</h3>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
{{--                            <button type="button" class="btn btn-primary" onclick="history.back();">Back</button>--}}
                            <button type="button" class="btn btn-primary float-right" onclick="window.print();">Print</button>
                            <a class="float-right" href="{{url('staff/dashboard')}}"><button type="button" class="btn btn-primary" style="margin-right: 3px;">Back</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">

                                <div class="col-md-6 float-right">
                                    <label for="">Order Status</label>
                                    <select name="order_status" id="order_status" class="form-control" data-order_id="{{$order->id}}">
                                        <option value="Pending" @if($order->order_status == 'Pending') selected @endif>Pending</option>
                                        <option value="Processing" @if($order->order_status == 'Processing') selected @endif>Processing</option>
                                        <option value="Delivered" @if($order->order_status == 'Delivered') selected @endif>Delivered</option>
                                        <option value="Completed" @if($order->order_status == 'Completed') selected @endif>Completed</option>
                                        <option value="Cancelled" @if($order->order_status == 'Cancelled') selected @endif>Cancelled</option>
                                    </select>

                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title text-center"><i class="fa fa-shopping-cart"></i> Order Details</h3>
                                        </div>
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <td style="width: 2%;">
                                                    <button data-toggle="tooltip" title="Order Number" class="btn btn-info btn-xs" data-original-title="Date Added"><i class="fa fa-info-circle fa-fw"></i> </button>
                                                </td>
                                                <td>#{{$order->order_number}}</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 2%;">
                                                    <button data-toggle="tooltip" title="Payment Method" class="btn btn-info btn-xs" data-original-title="Date Added"><i class="fa fa-money fa-fw"></i> </button>
                                                </td>
                                                <td>{{$order->payment->payment_method ?? ''}}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button data-toggle="tooltip" title="Order Date" class="btn btn-info btn-xs" data-original-title="Date Added"><i class="fa fa-calendar fa-fw"></i></button>
                                                </td>
                                                <td>{{date('d-M-Y H:i:s', strtotime($order->created_at))}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title text-center"><i class="fa fa-user"></i> Customer Details</h3>
                                        </div>
                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <td style="width: 1%;">
                                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="Customer"><i class="fa fa-user fa-fw"></i></button>
                                                </td>
                                                <td>
                                                    {{$order->name ?? $order->user->name ?? ''}}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="E-Mail"><i class="fa fa-envelope-o fa-fw"></i></button></td>
                                                <td>
                                                    @if($order->email != null)
                                                        <a href="mailto:{{$order->email}}">{{$order->email}}</a>
                                                    @else
                                                        <a href="mailto:{{$order->user->email}}">{{$order->user->email}}</a>
                                                    @endif

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs" data-original-title="Telephone"><i class="fa fa-phone fa-fw"></i></button></td>
                                                <td>
                                                    {{$order->phone ?? $order->user->phone ?? ''}}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td style="width: 50%;;font-weight: bold" class="text-left">Delivery Address</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-left">
                                                <strong>Address</strong> : {{$order->address ?? ''}}
                                                <br>
                                                <strong>Near Landmark</strong> : {{$order->nearest_landmark}}
                                                <br>
                                                <strong>Location</strong> : {{$order->location}}
                                                <br>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title text-center"> Order Item Details</h3>
                                </div>
                                <div class="table-responsive-sm">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th>Item</th>
                                            <th class="right">Size</th>
                                            <th class="right">Unit Cost</th>
                                            <th class="center">Qty</th>
                                            <th class="center">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $counter = 1;
                                            $subTotal = 0;
                                        @endphp
                                        @forelse($order->orderItems as $orderItem)
                                            <tr>
                                                <td class="center">{{$counter++}}</td>
                                                <td class="left strong">
                                                    {{$orderItem->product->name}}
                                                    <br>
                                                </td>
                                                <td class="right text-capitalize">{{$orderItem->size}}</td>
                                                <td class="right text-capitalize">{{$orderItem->price}}</td>
                                                <td class="center">{{$orderItem->quantity}}</td>
                                                <td class="center">{{$orderItem->quantity*$orderItem->price}}</td>
                                            </tr>
                                        @empty

                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-sm-5">
                                    </div>
                                    <div class="col-lg-4 col-sm-5 ml-auto">
                                        <table class="table table-clear">
                                            <tbody>

                                            <tr>
                                                <td class="left">
                                                    <strong>Subtotal</strong>
                                                </td>
                                                <td class="right">{{$order->subtotal}}</td>
                                            </tr>
                                            @if($order->discount > 0)
                                                <tr>
                                                    <td class="left">
                                                        <strong>Discount</strong>
                                                        <span>&nbsp{{isset($order->coupon) ? '(Coupon: '.$order->coupon->code.')' :''}}</span>
                                                    </td>
                                                    <td class="right">{{$order->discount}}</td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="left">
                                                    <strong>Delivery Charges</strong>
                                                </td>
                                                <td class="right">{{$order->delivery_fee}}</td>
                                            </tr>
                                            <tr>
                                                <td class="left">
                                                    <strong>Tax</strong>
                                                </td>
                                                <td class="right">{{$order->tax}}</td>
                                            </tr>

                                            <tr>
                                                <td class="left">
                                                    <strong>Total</strong>
                                                </td>
                                                <td class="right">
                                                    <strong>{{$order->total_amount}}</strong>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('change','#order_status',function(){
            let id = $(this).data('order_id');
            let val = $(this).val();

            $.ajax({
                type:"post",
                url:`{{url(request()->segment(1).'/changeOrderStatus')}}/${id}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    val:val
                },
                success: function (data) {
                    if(data === false) {
                        toastr.error('Something Went Wrong..!!');
                    }else{
                        toastr.success('Record Status Updated Successfully');
                    }
                }
            })
        });
    </script>
@endsection
