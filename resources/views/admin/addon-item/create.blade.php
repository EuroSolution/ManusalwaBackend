@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Addon Item')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Addon Item</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Addon Item</li>
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
                                <h3 class="card-title">Addon Item</h3>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editAddonItems',$content->id):route('admin.addAddonItems')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('msg'))
                                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                                    @endif
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Select Addon Group</label>
                                            <select class="form-control  @error('addon_group') is-invalid @enderror" name="addon_group" id="addon_group">
                                                <option value="">Select</option>
                                                @foreach($addonGroups as $addonGroup)
                                                    <option {{(old('addon_group')==$addonGroup->id) || (isset($content) && $content->addon_group_id == $addonGroup->id) ? 'selected' : ''}}
                                                            value="{{$addonGroup->id}}">{{$addonGroup->name ?? ''}}</option>
                                                @endforeach
                                            </select>
                                            @error('addon_group')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{$content->name?? old('name')}}" placeholder="Addon Name" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                        <div class="form-group">
                                            <label for="name">Price</label>
                                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{$content->price?? old('price')}}" placeholder="Price" required>
                                            @error('price')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="category">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description" required>{{$content->description ??old('description')}}</textarea>
                                            @error('description')
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
                                                            <input type="file" class="custom-file-input" name="file" id="category-image">
                                                            <label class="custom-file-label" for="category-image">Choose file</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" >
                                                <img src="{{$content->image ?? asset('admin/dist/img/placeholder.png')}}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                            </div>

                                        </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.addonItems')}}" class="btn btn-warning btn-md">Cancel</a>
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
