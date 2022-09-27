@extends('admin.layouts.app')
@section('title', 'Add Product')
@section('section')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('admin/dropzone/dist/basic.css') }}">

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Product Form</li>
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
{{--                                <h3 class="card-title">Product</h3>--}}
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#product" role="tab" data-toggle="tab">General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#terpenes" role="tab" data-toggle="tab">Terpenes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#additionalImages" role="tab" data-toggle="tab">Additional Images</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#related_products" role="tab" data-toggle="tab">Related Products</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#products_promotion" role="tab" data-toggle="tab">Product For Promotion</a>
                                    </li>
                                </ul>
                            </div>
                            <form class="category-form" method="post" action="{{route('product.store')}}" enctype="multipart/form-data">
                            <div class="tab-content ">
                                <div class="tab-pane active" role="tabpanel" class="tab-pane fade in active" id="product">

                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Main Category*</label>
                                                    <select class="form-control {{ $errors->has('main_category') ? 'has-error' : ''}}" name="main_category" id="main-category" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($mainCategories as $category)
                                                            <option value="{{$category->id}}" @if(old('main_category') == $category->id) {{ 'selected' }} @endif>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {!! $errors->first('main_category', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Sub Category</label>
                                                    <select class="form-control" name="sub_category" id="sub-category"></select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Brands*</label>
                                                    <select class="form-control {{ $errors->has('brand') ? 'has-error' : ''}}" name="brand" id="brand">
                                                        <option value="">Select Brand</option>
                                                        @foreach($manufacturers as $manufacturer)
                                                            <option value="{{$manufacturer->id}}"  @if(old('brand') == $manufacturer->id) {{ 'selected' }} @endif>{{$manufacturer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {!! $errors->first('brand', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Featured/New</label>
                                                    <select name="product_featured" id="" class="form-control">
                                                        <option value="Feature" @if(old('product_featured') == "Feature") {{ 'selected' }} @endif>Featured</option>
                                                        <option value="New"  @if(old('product_featured') == "New") {{ 'selected' }} @endif>New</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Name*</label>
                                                    <input type="text" name="product_name" placeholder="Product Name" class="form-control {{ $errors->has('product_name') ? 'has-error' : ''}}" value="{{old('product_name')}}" >
                                                    {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Price*</label>
                                                    <input type="text" step="0.01" id="price" name="price" placeholder="Price" class="form-control numberField {{ $errors->has('price') ? 'has-error' : ''}}" value="{{old('price')}}"  required>
                                                    {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Discounted Price*</label>
                                                    <input type="text" step="0.00" id="discounted_price" name="discounted_price" placeholder="Current Price" class="form-control numberField" value="{{old('discounted_price')}}">

                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Current Price*</label>
                                                    <input type="text" step="0.01" id="current_price" name="current_price" placeholder="Current Price" class="form-control numberField {{ $errors->has('current_price') ? 'has-error' : ''}}" value="{{old('current_price')}}"  readonly required>
                                                    {!! $errors->first('current_price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
{{--                                                <div class="col">--}}
{{--                                                    <label for="exampleInputEmail1">Product Sale</label>--}}
{{--                                                    <input type="checkbox" name="product_sale" class="form-control" id="product_sale" style="height: 20px;width: 20px;" value="yes" @if(old('product_sale') == 'yes') {{ 'checked' }} @endif>--}}
{{--                                                </div>--}}
{{--                                                <div class="col">--}}
{{--                                                    <label for="exampleInputEmail1">Sale(%)</label>--}}
{{--                                                    <input type="number" name="product_sale_percentage" placeholder="10" class="form-control" readonly id="product_sale_percentage" value="{{old('product_sale_percentage')}}" required>--}}
{{--                                                </div>--}}
{{--                                                <div class="col">--}}
{{--                                                    <label for="exampleInputEmail1">Product Stock</label>--}}
{{--                                                    <input type="checkbox" name="product_stock" class="form-control" id="product_stock" style="height: 20px;width: 20px;" value="yes" @if(old('product_stock') == 'yes') {{ 'checked' }} @endif>--}}
{{--                                                </div>--}}
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product SKU*</label>
                                                    <input type="text" name="product_sku" placeholder="Product SKU" class="form-control {{ $errors->has('product_sku') ? 'has-error' : ''}}" id="product_sku" value="{{old('product_sku')}}"  required>
                                                    <span id="sku_span"></span>
                                                    {!! $errors->first('product_sku', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Stock Qty</label>
                                                    <input type="number" name="product_stock_qty" class="form-control" placeholder="10" id="product_stock_qty" value="{{old('product_stock_qty')}}" required>
                                                </div>

                                                <div class="col">
                                                    <label for="switch">Status</label>
                                                    <label class="switch"><input type="checkbox" @if(old('status') == '1') {{ 'checked' }} @endif data-id="" id="status-switch" name="status" value="1">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Location*</label>
                                                    <select name="location" class="form-control {{ $errors->has('location') ? 'has-error' : ''}}" id="location" required>
                                                        <option value="">Select</option>
{{--                                                        <option value="both" @if(old('location') == 'both') selected @endif>Both</option>--}}
                                                        <option value="Chico" @if(old('location') == 'Chico') selected @endif>Shop Chico</option>
                                                        <option value="Sacramento" @if(old('location') == 'Sacramento') selected @endif>Shop Sacramento</option>
                                                    </select>
                                                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">THC Potency (%)</label>
                                                    <input type="text" step="0.01" name="thc_potency" class="form-control numberField" id="thc_potency" value="{{old('thc_potency')}}" placeholder="THC Potency">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">CBD Potency (%)</label>
                                                    <input type="text" step="0.01" name="cbd_potency" class="form-control numberField" id="cbd_potency" value="{{old('cbd_potency')}}" placeholder="CBD Potency">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Lineage*</label>
                                                    <select name="lineage" class="form-control" id="lineage">
                                                        <option value="">Select</option>
                                                        <option value="cbd" @if(old('lineage') == 'cbd') selected @endif>CBD</option>
                                                        <option value="sativa" @if(old('lineage') == 'sativa') selected @endif>Sativa</option>
                                                        <option value="sativa-dominant" @if(old('lineage') == 'sativa-dominant') selected @endif>Sativa Dominant</option>
                                                        <option value="hybrid" @if(old('lineage') == 'hybrid') selected @endif>Hybrid</option>
                                                        <option value="indica" @if(old('lineage') == 'indica') selected @endif>Indica</option>
                                                        <option value="indica-dominant" @if(old('lineage') == 'indica-dominant') selected @endif>Indica Dominant</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Length (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="length" placeholder="Length" class="form-control numberField @error('length') is-invalid @enderror" id="length"  value="{{old('length')}}">
                                                    @error('length') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Width (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="width" class="form-control numberField @error('width') is-invalid @enderror" placeholder="Width" id="width" value="{{old('width')}}">
                                                    @error('width') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Height (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="height" class="form-control numberField @error('height') is-invalid @enderror" id="height" placeholder="Height" value="{{old('height')}}">
                                                    @error('height') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Weight*</label>
                                                    <input type="text" step="0.01" min="1.0" name="weight" class="form-control numberField @error('weight') is-invalid @enderror" id="weight" value="{{old('weight')}}" placeholder="Weight">
                                                    @error('weight') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                            </div>
{{--                                            <br>--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-md-12">--}}
{{--                                                    <label for="category">Detail</label>--}}
{{--                                                    <textarea class="form-control {{ $errors->has('detail') ? 'has-error' : ''}}" name="detail" id="detail" placeholder="Detail" required>{{old('detail')}}</textarea>--}}
{{--                                                    {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="category">Description*</label>
                                                    <textarea class="form-control {{ $errors->has('main_category') ? 'has-error' : ''}}" name="description" id="description" placeholder="Description" required>{{old('description')}}</textarea>
                                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="category">Meta Title*</label>
                                                    <input type="text" class="form-control {{ $errors->has('meta-title') ? 'has-error' : ''}}" name="meta-title" id="meta-title"  value="{{old('meta-title')}}" placeholder="Meta Title" required >
                                                    {!! $errors->first('meta-title', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="category">Meta Description*</label>
                                                    <textarea class="form-control {{ $errors->has('meta-description') ? 'has-error' : ''}}" name="meta-description" id="meta-description" placeholder="Meta Description" required>{{old('meta-description')}}</textarea>
                                                    {!! $errors->first('meta-description', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="category">Meta Keywords*</label>
                                                    <textarea class="form-control {{ $errors->has('meta-keywords') ? 'has-error' : ''}}" name="meta-keywords" id="meta-keywords"  placeholder="Meta Keywords" required>{{old('meta-keywords')}}</textarea>
                                                    {!! $errors->first('meta-keywords', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Product Image</th>
                                                        <th>Select Image</th>
                                                    </tr>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img src="{{asset('admin/images/placeholder.png')}}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input"  name="product_image_first" id="gallery_0" onchange="PreviewImage('0')" accept="image/*" required>
                                                                        <label class="custom-file-label" for="category-image">Choose file</label>
                                                                    </div>
                                                                    {!! $errors->first('product_image_first', '<p class="help-block">:message</p>') !!}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="gallery"></div>
                                        </div>

                                        <!-- /.card-body -->
                                </div>
                                <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="terpenes">
                                    <div class="col-md-12 text-right">
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <th>Icon</th>
                                            <th>Select Icon</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                        <tbody id="add_more_terpene">
                                        <tr>
                                            <td class="col-md-1">
                                                <img src="{{asset('admin/images/placeholder.png')}}" alt="" id="tImg_1" style="height: 50px;width: 50px;">
                                            </td>
                                            <td class="col-md-3">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="terpenes_icons[]" id="tgallery_1" onchange="PreviewTerpeneImage(1)" accept="image/*">
                                                        <label class="custom-file-label" for="category-image">Choose file</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="">
                                                <input type="text" class="form-control" name="terpenes_titles[]" value=""/>
                                            </td>
                                            <td class="">
                                                <input type="text" class="form-control" name="terpenes_descs[]" value=""/>
                                            </td>
                                            <td class="col-md-1">
                                                <input type="button" class="btn btn-md btn-primary" value="+" onclick="addMoreTerpene()"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="additionalImages">
                                    <div class="col-md-12 text-right">
                                    </div>
                                    <table class="table">
                                        <tr>
                                            <th>Product Image</th>
                                            <th>Select Image</th>
                                        </tr>
                                        <tbody id="add_more">
                                        <tr >
                                            <td class="col-md-2">
                                                <img src="{{asset('admin/images/placeholder.png')}}" alt="" id="img_1" style="height: 150px;width: 150px;">
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="product_image[]" id="gallery_1" onchange="PreviewImage('1')" accept="image/*">
                                                        <label class="custom-file-label" for="category-image">Choose file</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <input type="button" class="btn btn-md btn-primary" id="addMoreBtn" value="+" onclick="addMorePictures(1)"/>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="related_products">
                                    <div class="col-md-12 text-right">
                                        <input type="button" class="btn btn-primary btn-sm" value="Add Related Product" onclick="addMoreRelatedProducts()" style="margin-top: 10px;margin-bottom: 10px;">
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="add_more_related">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="products_promotion">
                                    <div class="col-md-12 text-right">
                                        <input type="button" class="btn btn-primary btn-sm" value="Add Product" onclick="addProductForPromotion()" style="margin-top: 10px;margin-bottom: 10px;">
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="promotion_product_table">
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary" id="submit_btn" style="">Submit</button>
                                    <a href="{{route('product.index')}}" class="btn btn-warning" id="" style="">Cancel</a>
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

    <script src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/dropzone/dist/dropzone.js') }}"></script>
    <script type="text/javascript">
        window.onload = function () {
            CKEDITOR.replace('description', {});
        };


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Dependent Category
        $(document).ready(function () {
            $('#main-category').on('change',function(e) {
                var cat_id = e.target.value;
                $.ajax({
                    url:"{{ route('getSubCategories') }}",
                    type:"Get",
                    data: {
                        cat_id: cat_id
                    },
                    success:function (data) {
                        $('#sub-category').empty();
                        $.each(data.subcategories,function(index,subcategory){
                            $('#sub-category').append('<option value="'+subcategory.id+'">'+subcategory.name+'</option>');
                        })
                    }
                })
            });

            //Check Product SKU
            $('#product_sku').on('blur',function(e) {
                var sku = e.target.value;
                $.ajax({
                    url:"{{ route('checkProductSku') }}",
                    type:"Get",
                    data: {
                        sku: sku
                    },
                    success:function (data) {
                        if(data.product_sku > 0){
                            $('#sku_span').html(`<p style="color:red">SKU Already exist!</p>`);
                            $(':input[type="submit"]').prop('disabled', true);
                        }else{
                            $('#sku_span').empty();
                            $(':input[type="submit"]').prop('disabled', false);
                        }

                    }
                })
            });

            //Check Product Slug
            $('#product_slug').on('blur',function(e) {
                var slug = e.target.value;
                $.ajax({
                    url:"{{ route('checkProductSlug') }}",
                    type:"Get",
                    data: {
                        slug: slug
                    },
                    success:function (data) {
                        if(data.product_slug > 0){
                            $('#slug_span').html(`<p style="color:red">SLUG Already exist!</p>`);
                                $(':input[type="submit"]').prop('disabled', true);
                        }else{
                            $('#slug_span').empty();
                            $(':input[type="submit"]').prop('disabled', false);
                        }

                    }
                })
            });

            $('#product_sale').on('click',function (e){
                if($('#product_sale').prop('checked') == true){
                    $('#product_sale_percentage').prop('readonly', false);
                }else{
                    $('#product_sale_percentage').prop('readonly', true);
                }
            });

            $('#product_stock').on('click',function (e){
                if($('#product_stock').prop('checked') == true){
                    $('#product_stock_qty').prop('readonly', false);
                }else{
                    $('#product_stock_qty').prop('readonly', true);
                }
            });
        });

        var counter = 1;
        function addMorePictures(){
                counter++;
                $('#add_more').append(`<tr id="row_${counter}">
                                        <td class="col-md-2">
                                            <img src="{{asset('admin/images/placeholder.png')}}" alt="" id="img_${counter}" style="height: 150px;width: 150px;">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="product_image[]"  id="gallery_${counter}" onchange="PreviewImage('${counter}')" accept="image/*">
                                                    <label class="custom-file-label" for="category-image">Choose file</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="button" class="btn btn-danger btn-md" id="removeMoreBtn" onclick="removeImgRow('${counter}')" value="-"/>
                                        </td>
                                    </tr>`);

        }

        function removeImgRow(counter){
            $('#row_'+counter).remove();
        }

        var terpeneCounter = 1;
        function addMoreTerpene(){
            terpeneCounter++;
            if (terpeneCounter > 6){
                alert('You can not add more than 6 Terpenes');
            }else {
                $("#add_more_terpene").append(
                    `<tr id="trow_${terpeneCounter}">
                    <td class="col-md-1">
                        <img src="{{asset('admin/images/placeholder.png')}}" alt="" id="tImg_${terpeneCounter}" style="height: 50px;width: 50px;">
                    </td>
                    <td class="col-md-3">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="terpenes_icons[]" id="tgallery_${terpeneCounter}" onchange="PreviewTerpeneImage(${terpeneCounter})" accept="image/*">
                                <label class="custom-file-label" for="category-image">Choose file</label>
                            </div>
                        </div>
                    </td>
                    <td class="">
                        <input type="text" class="form-control" name="terpenes_titles[]" value=""/>
                    </td>
                    <td class="">
                        <input type="text" class="form-control" name="terpenes_descs[]" value=""/>
                    </td>
                    <td class="col-md-1">
                        <input type="button" class="btn btn-danger btn-md" onclick="removeTerpeneRow('${terpeneCounter}')" value="-"/>
                    </td>
                </tr>`
                );
            }
        }

        function removeTerpeneRow(counter){
            $('#trow_'+counter).remove();
            terpeneCounter --;
        }
        function PreviewTerpeneImage(counter) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById('tgallery_'+counter).files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById('tImg_'+counter).src = oFREvent.target.result;
            };
        }

        function PreviewImage(counter) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById('gallery_'+counter).files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById('img_'+counter).src = oFREvent.target.result;
            };
        }

        /* Related product functions start */
        var counter2 = 1;
        function addMoreRelatedProducts(){

            $("#add_more_related").append(`<tr id="row_related_${counter2}" class="row_related_prod"><td>
                <select id="related_prod_id_${counter2}" class="form-control related_prod" name="related_prod_id[]" required>
                   <option value="">Select Product</option>
                   @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->product_name}}</option>
                   @endforeach
                </select>
                </td>
                <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeRelatedProdRow(${counter2})"></td>
                </tr>`);
            makeRelatedProdArray();
            removeRelatedProdOption(counter2);
            counter2++;
        }

        var relatedProdOptions = [];
        function makeRelatedProdArray() {
            $('.row_related_prod').each(function (i, o) {
                var closestParent = $(this).closest('tr');
                var value = closestParent.find('.related_prod').val();
                if (value != null && value !== '' && relatedProdOptions.includes(value) === false) {
                    relatedProdOptions.push(value);
                }
            });
        }
        makeRelatedProdArray();
        function removeRelatedProdOption(id){
            relatedProdOptions.forEach(function (i, v) {
                $("#related_prod_id_"+id+" option[value='"+i+"']").remove();
            })
        }
        function removeRelatedProdRow(counter){
            var value = $('#row_related_'+counter).find('.related_prod').val();
            if (value != null && value !== '' && relatedProdOptions.includes(value) === true) {
                const index = relatedProdOptions.indexOf(value);
                if (index > -1) {
                    relatedProdOptions.splice(index, 1);
                }
            }
            $('#row_related_'+counter).remove();
        }
        /* Related product functions end */

        var counterPP = 1;
        function addProductForPromotion(){
            $("#promotion_product_table").append(`<tr id="row_pp_table_${counterPP}" class="row_pp_table"><td>
                <select id="row_pp_table_id_${counterPP}" class="form-control promotion_products" name="promotion_products[]" required>
                   <option value="">Select Product</option>
                   @foreach($products as $product)
                     <option value="{{$product->id}}" data-price="{{$product->current_price}}">{{$product->product_name}}</option>
                   @endforeach
                </select>
            </td>
            <td><input type="text" step="0.01" class="form-control numberField price" name="promotion_products_price[]" required></td>
            <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeProdPromotionRow(${counterPP})"></td>
                </tr>`);

            makeProductPromotionArray()
        }
        function removeProdPromotionRow(counter){
            $('#row_pp_table_'+counter).remove();
        }
        var promotedProdOptions = []
        function makeProductPromotionArray(){
            $('.row_pp_table').each(function (i, o) {
                var closestParent = $(this).closest('tr');
                var value = closestParent.find('.promotion_products').val();
                if (value != null && value !== '' && promotedProdOptions.includes(value) === false) {
                    promotedProdOptions.push(value);
                }
            });
        }

        $("body").on('change', '.promotion_products', function () {
            let price = $(".promotion_products option:selected").data('price');
            $(this).parents('tr').find('.price').val(price)
        })
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

            $("#price").focusout(function() {
                var discount = $("#discounted_price").val();
                if (discount != '' && discount != undefined && discount > 0) {
                    $("#current_price").val(discount);
                } else {
                    $("#current_price").val($(this).val());
                }
            });
            $("#discounted_price").focusout(function() {
                var price = $("#price").val();
                if ($(this).val() != '' && $(this).val() != undefined && $(this).val() > 0) {
                    $("#current_price").val($(this).val());
                } else {
                    $("#current_price").val(price);
                }
            })
        });
    </script>
@endsection
