@extends('admin.layout')
@section('title', 'Edit Deal')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Deal</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Edit Deal</li>
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
                                        <a class="nav-link active" aria-current="page" href="#deal" role="tab" data-toggle="tab">Deal Detail</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#products" role="tab" data-toggle="tab">Products</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#addons" role="tab" data-toggle="tab">Deal Addons</a>
                                    </li>

                                </ul>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.editDeals', $content->id)}}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="deal">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{old('name') ?? $content->name ?? ''}}" placeholder="Name">
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
                                                <input type="text" step="0.01" class="form-control numberField" name="price" id="price" value="{{old('price') ?? $content->price??0.00}}" placeholder="Price">
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
                                                    <img src="{{$content->image ?? asset('admin/dist/img/placeholder.png')}}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="products">
                                        <div class="card-body">
                                            <div class="col-md-12 text-right">
                                                <input type="button" class="btn btn-primary btn-sm" value="Add Product" onclick="addMoreProducts()" style="margin-bottom: 10px;"/>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Size (Optional)</th>
                                                    <th>Quantity</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_more_products">
                                                    @php $prodcutCount = 0; @endphp
                                                    @if ($content->dealItems != null && !empty($content->dealItems))
                                                    @foreach ($content->dealItems as $dkey => $dealItem)
                                                        @php $prodcutCount++; @endphp
                                                        <tr id="row_product_{{$dkey}}" class="row_product">
                                                            <td>
                                                                <select class="form-control" name="categories[{{$dkey}}]">
                                                                    <option value="">Select</option>
                                                                    @foreach($categories as $category)
                                                                        <option value="{{$category->id}}" @if($category->id == $dealItem->category_id) selected @endif>{{$category->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="prod_size[{{$dkey}}]">
                                                                    @foreach($sizes as $sizeKey => $sizeVal)
                                                                        <option value="{{$sizeVal}}" @if($sizeVal == $dealItem->size) selected @endif>{{$sizeVal}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control numberField" name="prod_quantity[{{$dkey}}]" placeholder="Quantity" value="{{$dealItem->quantity??'1'}}"></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeProductRow({{$dkey}})"></td>
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
                                                    <th>Addon Item</th>
                                                    <th>Quantity</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="add_more_addons">
                                                    @php $attrCount = 0; @endphp
                                                    @if($content->dealAddons != null && !empty($content->dealAddons))
                                                    @foreach($content->dealAddons as $atKey => $dealAddon)
                                                        @php $attrCount++; @endphp
                                                        <tr id="row_attr_{{$atKey}}" class="row_prod_attr">
                                                            <td><select id="attribute_id_{{$atKey}}" class="form-control" name="addons[]" onchange="getAddonItems({{$atKey}}, this.value)">
                                                                    <option value="">Select</option>
                                                                    @foreach($addons as $addon)
                                                                        <option value="{{$addon->id}}" @if($addon->id == $dealAddon->addon_group_id) selected @endif>{{$addon->name}}</option>
                                                                    @endforeach
                                                                </select></td>
                                                            <td><select id="addon_item_id_{{$atKey}}" class="form-control" name="addon_items[]">
                                                                    @foreach($addons as $addon2)
                                                                        @foreach($addon2->addonItems as $attItem)
                                                                            <option value="{{$attItem->id}}" @if($attItem->id == $dealAddon->addon_item_id) selected @endif>{{$attItem->name}}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td><input type="text" class="form-control numberField" name="addon_quantity[]" value="{{$dealAddon->quantity??''}}" placeholder="Quantity"></td>
                                                            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAttributesRow({{$atKey}})"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    <a href="{{route('admin.deals')}}" class="btn btn-warning btn-md">Cancel</a>
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
        var counter = {{$prodcutCount??1}};
        function addMoreProducts(){
            $("#add_more_products").append(`<tr id="row_product_${counter}" class="row_product">
            <td>
                <select class="form-control" name="categories[]">
                    <option value="">Select</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </td>
            {{--<td>--}}
            {{--    <select class="form-control" name="products[]">--}}
            {{--        <option value="">Select</option>--}}
            {{--        @foreach($products as $product)--}}
            {{--            <option value="{{$product->id}}">{{$product->name}}</option>--}}
            {{--        @endforeach--}}
            {{--    </select>--}}
            {{--</td>--}}
            <td><select class="form-control" name="prod_size[]">
                @foreach($sizes as $sizeKey => $sizeVal)
                    <option value="{{$sizeVal}}">{{$sizeVal}}</option>
                @endforeach
            </select></td>
            <td><input type="text" class="form-control numberField" name="prod_quantity[]" placeholder="Quantity"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeProductRow(${counter})"></td>
            </tr>`);
            counter++;
        }
        function removeProductRow(index){
            $('#row_product_'+index).remove();
        }
        var counter2 = {{$attrCount??1}};
        function addMoreAddon(){
            $("#add_more_addons").append(`<tr id="row_addon_${counter2}" class="row_prod_addon">
            <td>
                <select class="form-control" name="addons[]" onchange="getAddonItems(${counter2}, this.value)">
                    <option value="">Select</option>
                    @foreach($addons as $addon)
                    <option value="{{$addon->id}}">{{$addon->name}}</option>
                    @endforeach
            </select>
            </td>
            <td><select id="addon_item_id_${counter2}" class="form-control" name="addon_items[]"></select>
            </td>
            <td><input type="text" class="form-control numberField" name="addon_quantity[]" placeholder="Quantity"></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeAddonRow(${counter2})"></td>
        </tr>`);
            counter2++;
        }

        function getAddonItems(counter,val) {
            var addon_id = val;
            if(addon_id !== ''){
                $.ajax({
                    url:"{{ route('admin.getAddonItemsById') }}",
                    type:"get",
                    data: {
                        addon_id: addon_id
                    },
                    success:function (data) {
                        $('#addon_item_id_'+counter).empty();
                        $.each(data ,function(index,val){
                            $('#addon_item_id_'+counter).append('<option value="'+val.id+'">'+val.name+'</option>');
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

        $('#dealImage').on('change', function(){
            const [file] = dealImage.files
            if (file) {
                img_0.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
