@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Staff')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Delivery Time</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Delivery Time</li>
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
                                <h3 class="card-title">{{(isset($content->id) ? 'Edit' : 'Add')}} Delivery Time</h3>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editDeliveryTime',$content->id):route('admin.addDeliveryTime')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif

                                    <div class="form-group">
                                        <label for="day">Day</label>
                                        <input type="text" class="form-control @error('day') is-invalid @enderror" name="day" id="day" value="{{$content->day?? old('day')}}" placeholder="Day" required>
                                        @error('day')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="text" class="form-control @error('time') is-invalid @enderror" name="time" id="time" value="{{$content->time?? old('time')}}" placeholder="Time" required>
                                        @error('time')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>                        
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.deliveryTime')}}" class="btn btn-warning btn-md">Cancel</a>
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
    @section('script')
    <script>
    </script>
    @endsection
