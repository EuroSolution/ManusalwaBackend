@extends('admin.layout')
@section('title', 'Edit Addon Item')
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
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#general" role="tab" data-toggle="tab">General Detail</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#sizes" role="tab" data-toggle="tab">Addon Sizes</a>
                                    </li>
                                </ul>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.editAddonItems',$content->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="general">
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
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="sizes">
                                        <div class="card-body">
                                            <div class="col-md-12 text-right">
                                                <input type="button" class="btn btn-primary btn-sm" value="Add Size" onclick="addMoreSizes()" style="margin-bottom: 10px;"/>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Size</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_more_sizes">
                                                @php $countSizes = 0; @endphp
                                                @if($content->addonSizes != null && !empty($content->addonSizes))
                                                    @foreach($content->addonSizes as $aKey => $addonSize)
                                                        @php $countSizes++; @endphp
                                                        <tr id="row_size_{{$aKey}}" class="row_prod_size">
                                                            <td>
                                                                <select class="form-control" name="sizes[]" id="">
                                                                    @foreach ($sizes as $size)
                                                                        <option {{($size == $addonSize->size)?'selected':''}} value="{{$size}}">{{$size}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control numberField" value="{{$addonSize->price ?? ''}}" name="prices[]" placeholder="Price"></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow({{$aKey}})"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

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
@section('script')
    <script>
        var counter = {{$countSizes}};

        var sizes = {!! json_encode($sizes) !!}
        var sizesOption = '<option value="">Select</option>';
        for(a = 0; a<sizes.length; a++){
            sizesOption += "<option value'"+sizes[a]+"'>"+sizes[a]+"</option>";
        }

        function addMoreSizes(){
            if(!(counter <= 3)){
                alert('can not add more than four sizes');
                return false;
            }
            $("#add_more_sizes").append(`<tr id="row_size_${counter}" class="row_prod_size">
            <td>
                <select class="form-control" name="sizes[]">
                    ${sizesOption}
                </select>
            </td>
            <td><input type="text" class="form-control numberField" name="prices[]" placeholder="Price"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow(${counter})"></td>
            </tr>`);
                counter++;
        }
        function removeSizeRow(index){
            $('#row_size_'+index).remove();
            counter--;
        }

        $('#dealImage').on('change', function(){
            const [file] = dealImage.files
            if (file) {
                img0.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
