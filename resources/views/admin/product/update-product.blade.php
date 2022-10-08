@extends('admin.layout')
@section('title', 'Edit Product')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Product</li>
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
                            <form class="category-form" method="post" action="{{route('admin.editProduct', $content->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="product">
                                        <div class="card-body">
                                            @if(Session::has('msg'))
                                                <div class="alert alert-success">{{Session::get('msg')}}</div>
                                            @endif
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Select Category</label>
                                                <select class="form-control  @error('name') is-invalid @enderror" name="category" id="category">
                                                    <option value="">Select</option>
                                                    @foreach($categories as $category)
                                                        <option {{(old('category') == $category->id || $content->category_id == $category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name') ?? $content->name ?? ''}}" placeholder="Product Name" required>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description">{{old('description') ?? $content->description ?? ''}}</textarea>
                                                @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input type="text" step="0.01" class="form-control numberField" name="price" id="price" value="{{old('price') ?? $content->price ?? 0.00}}" placeholder="Product Price">
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
                                                    <img src="{{$content->image ?? asset('admin/dist/img/placeholder.png')}}" alt="" id="img0" style="height: 150px;width: 150px;">
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
                                                @php $sizeCount = 0; @endphp
                                                <tbody id="add_more_sizes">
                                                @if($content->sizes != null && !empty($content->sizes))
                                                    @foreach($content->sizes as $sKey => $size)
                                                        @php $sizeCount++; @endphp
                                                        <tr id="row_size_{{$sKey}}" class="row_prod_size">
                                                            <td>
                                                                <select class="form-control" name="sizes[{{$sKey}}]" id="">
                                                                    @foreach ($productSizes as $productSize)
                                                                        <option {{($productSize == $size->size)?'selected':''}} value="{{$productSize}}">{{$productSize}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control numberField" name="size_prices[{{$sKey}}]" placeholder="Price" value="{{$size->price ?? '0.00'}}"></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow({{$sKey}})"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
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
                                                @php $attrCount = 0; @endphp
                                                @if($content->productAttributes != null && !empty($content->productAttributes))
                                                    @foreach($content->productAttributes as $atKey => $productAttribute)
                                                        @php $attrCount++; @endphp
                                                        <tr id="row_attr_{{$atKey}}" class="row_prod_attr">
                                                            <td><select id="attribute_id_{{$atKey}}" class="form-control" name="attributes[]" onchange="getAttributeValues({{$atKey}}, this.value)">
                                                                    <option value="">Select</option>
                                                                    @foreach($attributes as $attribute)
                                                                        <option value="{{$attribute->id}}" @if($attribute->id == $productAttribute->attribute_id) selected @endif>{{$attribute->name}}</option>
                                                                    @endforeach
                                                                </select></td>
                                                            <td><select id="attribute_item_id_{{$atKey}}" class="form-control" name="attribute_items[]">
                                                                    @foreach($attributes as $attribute2)
                                                                        @foreach($attribute2->attributeItems as $attItem)
                                                                            <option value="{{$attItem->id}}" @if($attItem->id == $productAttribute->attribute_item_id) selected @endif>{{$attItem->name}}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                </select></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAttributesRow({{$atKey}})"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
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
                                                @php $addonCount = 0; @endphp
                                                @if($content->addons != null && !empty($content->addons))
                                                    @foreach($content->addons as $aKey => $addon)
                                                        @php $addonCount++; @endphp
                                                        <tr id="row_addon_{{$aKey}}" class="row_prod_addon">
                                                            <td><select class="form-control" name="addons[{{$aKey}}]">
                                                                    <option value="">Select</option>
                                                                    @foreach($addonItems as $addonItem)
                                                                        <option value="{{$addonItem->id}}" @if($addonItem->id == $addon->addon_item_id) selected @endif>{{$addonItem->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control numberField" name="addon_prices[{{$aKey}}]" placeholder="Price" value="{{$addon->price??'0.00'}}"></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAddonRow({{$aKey}})"></td>
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
        
        var counter = {{$sizeCount??1}};
        $(document).ready(function(){
            // alert(counter);
            
        });
        var productSizes = {!! json_encode($productSizes) !!}
        
        var sizes = '<option value="">Select</option>';
        for(a = 0; a<productSizes.length; a++){
            sizes += "<option value'"+productSizes[a]+"'>"+productSizes[a]+"</option>";
        }
        function addMoreSizes(){
            
            if(!(counter <= 3)){
                alert('can not add more than four sizes');
                return false;
            }
            $("#add_more_sizes").append(`<tr id="row_size_${counter}" class="row_prod_size">
                <td>
                <select class="form-control" name="sizes[]">
                    ${sizes}
                </select>
                </td>
                <td><input type="text" class="form-control" name="size_prices[]" placeholder="Price"></td>
                <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeSizeRow(${counter})"></td>
            </tr>`);;

            counter++
        }
        function removeSizeRow(index){
            $('#row_size_'+index).remove();
            --counter;
        }
        var counter2 = {{$addonCount??1}};
        function addMoreAddon(){
            $("#add_more_addons").append(`<tr id="row_addon_${counter2}" class="row_prod_addon">
                <td><select class="form-control" name="addons[]">
                    <option value="">Select</option>
                    @foreach($addonItems as $addon)
            <option value="{{$addon->id}}">{{$addon->name}}</option>
                    @endforeach
            </select></td>
            <td><input type="text" class="form-control" name="addon_prices[]" placeholder="Price"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAddonRow(${counter2})"></td>
            </tr>`);
            counter2++;
        }
        var counter3 = {{$attrCount??1}};
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

        $('#productImage').on('change', function(){
            const [file] = productImage.files
            if (file) {
                img0.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
