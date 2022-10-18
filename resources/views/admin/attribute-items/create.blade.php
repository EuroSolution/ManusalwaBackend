@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Attribute Item')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attribute Item</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Attribute Item</li>
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
                                <h3 class="card-title">Attribute Item</h3>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editAttributeItems',$content->id):route('admin.addAttributeItems')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Select Attribute Group</label>
                                            <select class="form-control  @error('attribute') is-invalid @enderror" name="attribute" id="attribute">
                                                <option value="">Select</option>
                                                @foreach($attributes as $attribute)
                                                    <option {{(old('attribute')==$attribute->id) || (isset($content) && $content->attribute_id == $attribute->id) ? 'selected' : ''}}
                                                            value="{{$attribute->id}}">{{$attribute->name ?? ''}}</option>
                                                @endforeach
                                            </select>
                                            @error('attribute')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{$content->name?? old('name')}}" placeholder="Attribute Item Name" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                        <div class="row">

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Image</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" name="file" id="dealImage">
                                                            <label class="custom-file-label" for="dealImage">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" >
                                                <img src="{{$content->image ?? asset('admin/dist/img/placeholder.png')}}" alt="" id="img0" style="height: 150px;width: 150px;">
                                            </div>

                                        </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.attributeItems')}}" class="btn btn-warning btn-md">Cancel</a>
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
