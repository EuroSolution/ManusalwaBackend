@extends('admin.layout')
@section('title', 'Add Area Code Charge')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Area Code Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Area Code Form</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Area Code</h3>
                            </div>
                            <form class="coupon-form" method="post" action="{{!empty($content->id)?route('admin.areaCodeEdit',$content->id):route('admin.areaCodeAdd')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
{{--                                        <div class="form-group">--}}
{{--                                            <label for="code">Area Code</label>--}}
{{--                                            <input type="text" class="form-control @error('code') is-invalid @enderror" name="area_code" id="code" value="{{$content->area_code??old('code')}}" placeholder="Area Code">--}}
{{--                                            @error('code')--}}
{{--                                            <span class="invalid-feedback" role="alert">--}}
{{--                                                <strong>{{ $message }}</strong>--}}
{{--                                            </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{$content->address?? old('address')}}" placeholder="Address">
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="delivery_charge">Delivery Charge</label>
                                            <input type="text" class="form-control @error('delivery_charge') is-invalid @enderror" name="delivery_charge" id="delivery_charge" value="{{$content->delivery_charge??old('delivery_charge')}}" placeholder="Dilavery Charge">
                                            @error('delivery_charge')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="min_amount">Minimum Amount</label>
                                            <input type="text" class="form-control @error('min_amount') is-invalid @enderror" name="min_amount" id="min_amount" value="{{$content->min_amount??old('min_amount')}}" placeholder="Minimun Amount">
                                            @error('min_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.areaCodeCharge')}}" class="btn btn-warning btn-md">Cancel</a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
