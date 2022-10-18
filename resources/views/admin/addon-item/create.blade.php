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
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#general" role="tab" data-toggle="tab">General Detail</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#sizes" role="tab" data-toggle="tab">Addon Sizes</a>
                                    </li>
                                </ul>
                            </div>
                            <form class="category-form" method="post" action="{{!empty($content->id)?route('admin.editAddonItems',$content->id):route('admin.addAddonItems')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="general">
                                        <div class="card-body">

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
                                                                <input type="file" class="custom-file-input" name="file" id="addonItemImage">
                                                                <label class="custom-file-label" for="addonItemImage">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" >
                                                    <img src="{{$content->image ?? asset('admin/dist/img/placeholder.png')}}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="sizes">
                                        <div class="card-body">
                                            <div class="col-md-12 text-right">
                                                <input type="button" class="btn btn-primary btn-sm addMoreSizes" value="Add Size" onclick="addMoreSizes()" style="margin-bottom: 10px;"/>
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
        var counter = 1;
        function addMoreSizes(){
            $("#add_more_sizes").append(`<tr id="row_size_${counter}" class="row_prod_size">
             <td><select class="form-control" name="sizes[]">
                        @foreach($sizes as $sizeKey => $sizeVal)
                <option value="{{$sizeVal}}">{{$sizeVal}}</option>
                        @endforeach
                </select></td>
            <td><input type="text" class="form-control numberField" name="prices[]" placeholder="Price"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow(${counter})"></td>
            </tr>`);
            counter++;
            if (counter > 4){
                $(".addMoreSizes").attr('disabled', 'disabled');
            }
        }
        function removeSizeRow(index){
            $('#row_size_'+index).remove();
            counter--;
            if (counter <= 4){
                $(".addMoreSizes").attr('disabled', false);
            }
        }

        $('#addonItemImage').on('change', function(){
            const [file] = addonItemImage.files
            if (file) {
                img_0.src = URL.createObjectURL(file)
            }
        });

        $('#add_more_sizes').click(function(){
            $(this).find('.numberField').click(function(){
                var $input = $(this);
                var $inputVal = ($input.val() === '') ? 0 : $input.val();
                var count = parseFloat($inputVal);
                if (count < 0 || isNaN(count)) {
                    count = 1;
                }
                $input.val(count);
                return false;
            });

            $(this).find('.numberField').focusout(function(){
                var $input = $(this);
                var $inputVal = ($input.val() === '') ? 0 : $input.val();
                var count = parseFloat($inputVal);
                if (count < 0 || isNaN(count)) {
                    count = 1;
                }
                $input.val(count);
                return false;
            });
        });
    </script>
@endsection
