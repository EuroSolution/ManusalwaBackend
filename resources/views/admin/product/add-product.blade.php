@extends('admin.layout')
@section('title', 'Add Product')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Add Product</li>
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
                                        <a class="nav-link active" aria-current="page" href="#product" role="tab" data-toggle="tab">Product Detail</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#sizes" role="tab" data-toggle="tab">Product Sizes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#attributes" role="tab" data-toggle="tab">Product Attributes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#addons" role="tab" data-toggle="tab">Product Addons</a>
                                    </li>

                                </ul>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.addProduct')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="product">
                                        <div class="card-body">
                                            @if(Session::has('msg'))
                                                <div class="alert alert-success">{{Session::get('msg')}}</div>
                                            @endif
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Select Category</label>
                                                <select class="form-control  @error('category') is-invalid @enderror" name="category" id="category">
                                                    <option value="">Select</option>
                                                    @foreach($categories as $category)
                                                        <option {{(old('category')==$category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name ?? ''}}</option>
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
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name')}}" placeholder="Product Name">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description">{{old('description')}}</textarea>
                                                @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input type="text" step="0.01" class="form-control numberField" name="price" id="price" value="{{old('price') ?? 0.00}}" placeholder="Product Price">
                                            </div>

                                            <div class="row">

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Product Image</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="file" id="productImage">
                                                                <label class="custom-file-label" for="productImage">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" >
                                                    <img src="{{asset('admin/dist/img/placeholder.png')}}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="sizes">
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

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="attributes">
                                        <div class="card-body">
                                            <div class="col-md-12 text-right">
                                                <input type="button" class="btn btn-primary btn-sm" value="Add Attribute" onclick="addMoreAttributes()" style="margin-bottom: 10px;"/>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Attribute</th>
                                                    <th>Attribute Item</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_more_attributes">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="addons">
                                        <div class="card-body">
                                            <div class="col-md-12 text-right">
                                                <input type="button" class="btn btn-primary btn-sm" value="Add Addon" onclick="addMoreAddon()" style="margin-bottom: 10px;"/>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Addon</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_more_addons">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.products')}}" class="btn btn-warning btn-md">Cancel</a>
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
        var productSizes = {!! json_encode($productSizes) !!}
        
        var sizes = '<option value="">Select</option>';
        for(a = 0; a<productSizes.length; a++){
            sizes += "<option value'"+productSizes[a]+"'>"+productSizes[a]+"</option>";
        }

        function addMoreSizes(){
            // console.log(counter);
            if(counter >= 4){
                alert('can not add more than three sizes');
                return false;
                // $(this).prop('disabled', true);
            }
            $("#add_more_sizes").append(`<tr id="row_size_${counter}" class="row_prod_size">
            <td>
            <select class="form-control" name="sizes[]">
                ${sizes}
            </select>
            </td>
            <td><input type="text" class="form-control numberField" name="size_prices[]" placeholder="Price"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow(${counter})"></td>
            </tr>`);
            counter++;
        }
        function removeSizeRow(index){
            $('#row_size_'+index).remove();
            --counter;
        }
        var counter2 = 1;
        function addMoreAddon(){
            $("#add_more_addons").append(`<tr id="row_addon_${counter2}" class="row_prod_addon">
        <td><select class="form-control" name="addons[]">
            <option value="">Select</option>
            @foreach($addonItems as $addon)
            <option value="{{$addon->id}}">{{$addon->name}}</option>
            @endforeach
            </select></td>
            <td><input type="text" class="form-control numberField" name="addon_prices[]" placeholder="Price"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAddonRow(${counter2})"></td>
        </tr>`);
            counter2++;
        }

        var counter3 = 1;
        function addMoreAttributes(){
            $("#add_more_attributes").append(`<tr id="row_attr_${counter3}" class="row_prod_attr">
        <td><select id="attribute_id_${counter3}" class="form-control" name="attributes[]" onchange="getAttributeValues(${counter3}, this.value)">
            <option value="">Select</option>
            @foreach($attributes as $attribute)
            <option value="{{$attribute->id}}">{{$attribute->name}}</option>
            @endforeach
            </select></td>
            <td><select id="attribute_item_id_${counter3}" class="form-control" name="attribute_items[]"></select></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAttributesRow(${counter3})"></td>
        </tr>`);
            counter3++;
        }
        function getAttributeValues(counter,val) {
            var attribute_id = val;
            if(attribute_id !== ''){
                $.ajax({
                    url:"{{ route('admin.getAttributeItemsById') }}",
                    type:"get",
                    data: {
                        attribute_id: attribute_id
                    },
                    success:function (data) {
                        $('#attribute_item_id_'+counter).empty();
                        $.each(data ,function(index,val){
                            $('#attribute_item_id_'+counter).append('<option value="'+val.id+'">'+val.name+'</option>');
                        })
                    }
                })
            }
        }
        function removeAddonRow(index){
            $('#row_addon_'+index).remove();
        }
        function removeAttributesRow(index){
            $('#row_attr_'+index).remove();
        }
        $(document).ready(function() {
            $(".numberField").click(function() {
                var $input = $(this);
                var $inputVal = ($input.val() === '') ? 0 : $input.val();
                var count = parseFloat($inputVal);
                if (count < 0 || isNaN(count)) {
                    count = 1;
                }
                $input.val(count);
                return false;
            });
            $(".numberField").focusout(function() {
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

        /* categoryImage.onchange = evt => {
            const [file] = categoryImage.files
            if (file) {
                img0.src = URL.createObjectURL(file)
            }
        } */

        $('#productImage').on('change', function(){
            const [file] = productImage.files
            if (file) {
                img_0.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
