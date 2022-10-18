@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Banner')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Banner Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Banner Form</li>
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
                                <h3 class="card-title">Banner</h3>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editBanner',$content->id):route('admin.addBanner')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sort</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{$content->sort_order ?? ''}}">
                                        @error('sort_order')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="row">

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Banner Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file @error('image') is-invalid @enderror">
                                                        <input type="file" class="custom-file-input" name="image"] id="dealImage">
                                                        <label class="custom-file-label" for="dealImage">Choose file</label>
                                                    </div>
                                                    @error('image')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" >
                                            <img src="{{asset(isset($content->image) ? $content->image : 'admin/dist/img/placeholder.png')}}" alt="" id="img0" style="height: 150px;width: 150px;">
                                        </div>

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.banners')}}" class="btn btn-warning btn-md">Cancel</a>
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
        $('#dealImage').on('change', function(){
            const [file] = dealImage.files
            if (file) {
                img0.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
