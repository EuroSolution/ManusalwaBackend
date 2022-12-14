@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Attribute Group')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Addon Group</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Attribute Group</li>
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
                                <h3 class="card-title">Attribute Group</h3>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editAttributes',$content->id):route('admin.addAttributes')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                    <div class="form-group">
                                        <label for="category">Select Category</label>
                                        <select class="form-control  @error('category') is-invalid @enderror" name="category" id="category">
                                            <option value="">Select</option>
                                            @foreach($categories as $category)
                                            @if (isset($content))
                                                <option {{(old('category') == $category->id || $content->category_id == $category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name ?? ''}}</option>
                                            @else    
                                                <option {{(old('category') == $category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name ?? ''}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{$content->name?? old('name')}}" placeholder="Attribute Name" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <input type="text" class="form-control @error('type') is-invalid @enderror" name="type" id="type" value="{{$content->type?? old('type')}}" placeholder="Attribute Type" required>
                                        @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.attributes')}}" class="btn btn-warning btn-md">Cancel</a>
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
