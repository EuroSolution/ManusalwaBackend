@extends('admin.layouts.app')
@section('title', 'Edit '. ($product->product_name ?? '') .' Product')
@section('section')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('admin/dropzone/dist/basic.css') }}">
    <style>
        .switch {
            position: relative;
            display: block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .help-block{
            color:red;
        }
        .has-error{
            border-block-color: red;
        }
    </style>
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
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
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
                            <form class="category-form" method="post" action="{{url('admin/product').'/'.$content->id}}" enctype="multipart/form-data">
                                <div class="tab-content ">
                                    <div class="tab-pane active" role="tabpanel" class="tab-pane fade in active" id="product">
                                        @method('put')
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Main Category*</label>
                                                    <select class="form-control {{ $errors->has('main_category') ? 'has-error' : ''}}" name="main_category" id="main-category" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($mainCategories as $category)
                                                            <option value="{{$category->id}}" @if($product->category_id == $category->id) selected @endif>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {!! $errors->first('main_category', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Sub Category</label>
                                                    <select class="form-control" name="sub_category" id="sub-category">
                                                        @foreach($subCategories as $subcategory)
                                                            <option value="{{$subcategory->id}}" @if($product->sub_category_id == $subcategory->id) selected @endif>{{$subcategory->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Brands*</label>
                                                    <select class="form-control {{ $errors->has('brand') ? 'has-error' : ''}}" name="brand" id="brand" required>
                                                        <option value="">Select Brand</option>
                                                        @foreach($manufacturers as $manufacturer)
                                                            <option value="{{$manufacturer->id}}" @if($manufacturer->id == $product->manufacturer_id) selected @endif>{{$manufacturer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {!! $errors->first('brand', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Featured/New</label>
                                                    <select name="product_featured" id="" class="form-control">
                                                        <option value="Feature" @if($product->product_type == "Featured") selected @endif>Featured</option>
                                                        <option value="New" @if($product->product_type == "New") selected @endif>New</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Name*</label>
                                                    <input type="text" name="product_name" placeholder="Product Name" class="form-control {{ $errors->has('product_name') ? 'has-error' : ''}}" value="{{$product->product_name}}" required>
                                                    {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
                                                </div>

                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Price*</label>
                                                    <input type="text" step="0.01" id="price" name="price" placeholder="Price" class="form-control numberField {{ $errors->has('price') ? 'has-error' : ''}}" value="{{$product->price ?? 0.00}}"  required>
                                                    {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Discounted Price*</label>
                                                    <input type="text" step="0.00" id="discounted_price" name="discounted_price" placeholder="Current Price" class="form-control numberField" value="{{$product->discounted_price ?? 0.00}}">

                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Current Price*</label>
                                                    <input type="text" step="0.01" id="current_price" name="current_price" placeholder="Current Price" class="form-control numberField {{ $errors->has('current_price') ? 'has-error' : ''}}" value="{{$product->current_price}}"  readonly required>
                                                    {!! $errors->first('current_price', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="row">

                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product SKU*</label>
                                                    <input type="text" name="product_sku" placeholder="Product SKU" class="form-control {{ $errors->has('product_sku') ? 'has-error' : ''}}" value="{{$product->sku}}" id="product_sku" required>
                                                    <span id="sku_span"></span>
                                                    {!! $errors->first('product_sku', '<p class="help-block">:message</p>') !!}
                                                </div>

                                                <div class="col">
                                                    <label for="exampleInputEmail1">Product Stock Qty</label>
                                                    <input type="text" name="product_stock_qty" class="form-control" placeholder="10" @if($product->product_stock == "no") readonly @endif id="product_stock_qty" value="{{$product->product_qty}}" required>
                                                </div>
                                                <div class="col">
                                                    <label for="switch">Status</label>
                                                    <label class="switch"><input type="checkbox" @if($product->status == 1) checked @endif data-id="" id="status-switch" name="status" value="1">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="location">Location*</label>
                                                    <select name="location" class="form-control {{ $errors->has('location') ? 'has-error' : ''}}" id="location" required>
                                                        <option value="">Select</option>
{{--                                                        <option value="both" @if($product->location == 'both') selected @endif>Both</option>--}}
                                                        <option value="Chico" @if($product->location == 'Chico') selected @endif>Shop Chico</option>
                                                        <option value="Sacramento" @if($product->location == 'Sacramento') selected @endif>Shop Sacramento</option>
                                                    </select>
                                                    {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">THC Potency (%)</label>
                                                    <input type="text" step="0.01" name="thc_potency" class="form-control numberField" id="thc_potency" value="{{$product->thc_potency??''}}" placeholder="THC Potency">
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">CBD Potency (%)</label>
                                                    <input type="text" step="0.01" name="cbd_potency" class="form-control numberField" id="cbd_potency" value="{{$product->cbd_potency??''}}" placeholder="CBD Potency">
                                                </div>
                                                <div class="col">
                                                    <label for="lineage">Lineage*</label>
                                                    <select name="lineage" class="form-control" id="lineage">
                                                        <option value="">Select</option>
                                                        <option value="cbd" @if($product->lineage == 'cbd') selected @endif>CBD</option>
                                                        <option value="sativa" @if($product->lineage == 'sativa') selected @endif>Sativa</option>
                                                        <option value="sativa-dominant" @if($product->lineage == 'sativa-dominant') selected @endif>Sativa Dominant</option>
                                                        <option value="hybrid" @if($product->lineage == 'hybrid') selected @endif>Hybrid</option>
                                                        <option value="indica" @if($product->lineage == 'indica') selected @endif>Indica</option>
                                                        <option value="indica-dominant" @if($product->lineage == 'indica-dominant') selected @endif>Indica Dominant</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Length (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="length" placeholder="Length" class="form-control numberField @error('length') is-invalid @enderror" id="length"  value="{{$product->length??0}}">
                                                    @error('length') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Width (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="width" class="form-control numberField @error('width') is-invalid @enderror" placeholder="Width" id="width" value="{{$product->width??0}}">
                                                    @error('width') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Height (inches)*</label>
                                                    <input type="text" step="0.01" min="1.0" name="height" class="form-control numberField @error('height') is-invalid @enderror" id="height" placeholder="Height" value="{{$product->height??0}}">
                                                    @error('height') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                                <div class="col">
                                                    <label for="exampleInputEmail1">Weight* </label>
                                                    <input type="text" step="0.01" min="1.0" name="weight" class="form-control numberField @error('weight') is-invalid @enderror" id="weight" value="{{$product->weight??0}}" placeholder="Weight">
                                                    @error('weight') <span class="invalid-feedback" role="alert"><strong>{{$message}}</strong></span> @enderror
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="category">Description*</label>
                                                    <textarea class="form-control {{ $errors->has('description') ? 'has-error' : ''}}" name="description" id="description" placeholder="Description" required>{{$product->description}}</textarea>
                                                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="category">Meta Title*</label>
                                                    <input type="text" class="form-control" name="meta-title" id="meta-title"  value="{{$product->product_meta_data->meta_tag_title ?? ''}}" placeholder="Meta Title">
                                                </div>
                                                <div class="col">
                                                    <label for="category">Meta Description*</label>
                                                    <textarea class="form-control" name="meta-description" id="meta-description" placeholder="Meta Description">{{$product->product_meta_data->meta_tag_description ?? ''}}</textarea>
                                                </div>
                                                <div class="col">
                                                    <label for="category">Meta Keywords*</label>
                                                    <textarea class="form-control" name="meta-keywords" id="meta-keywords"  placeholder="Meta Keywords">{{$product->product_meta_data->meta_tag_keywords ?? ''}}</textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Placeholder</th>
                                                        <th>Select Image</th>
                                                    </tr>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <img src="{{ productImage(@$product->product_image) }}" alt="" id="img_0" style="height: 150px;width: 150px;">
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input"  name="product_image_first" id="gallery_0" onchange="PreviewImage('0')"   accept="image/*">
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
                                </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="terpenes">
                                        <div class="col-md-12 text-right">
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <th colspan="5" class="text-right">
                                                    <input type="button" class="btn btn-primary" value="Add More Terpene" onclick="addMoreTerpene(1)"/>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Icon</th>
                                                <th>Select Icon</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody id="add_more_terpene">
                                            @php
                                                $terpeneCounter = 0;
                                            @endphp
                                            @forelse($product->terpenes as $tkey => $terpene)
                                                @php
                                                    $terpeneCounter++;
                                                @endphp
                                                <tr id="trow_{{$terpeneCounter}}">
                                                    <td class="col-md-1">
                                                        <img src="{{isset($terpene->image) ? asset($terpene->image) : asset('admin/images/placeholder.png')}}" alt="" id="tImg_{{$terpeneCounter}}" style="height: 50px;width: 50px;">
                                                        <input type="hidden" name="saved_terpenes_icons[]" value="{{$terpene->image}}">
                                                    </td>
                                                    <td class="col-md-3">
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="terpenes_icons[]" id="tgallery_{{$terpeneCounter}}" onchange="PreviewTerpeneImage({{$terpeneCounter}})" accept="image/*">
                                                                <label class="custom-file-label" for="category-image">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="">
                                                        <input type="text" class="form-control" name="terpenes_titles[]" value="{{$terpene->title??''}}"/>
                                                    </td>
                                                    <td class="">
                                                        <input type="text" class="form-control" name="terpenes_descs[]" value="{{$terpene->description}}"/>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <input type="button" class="btn btn-danger btn-md" onclick="removeTerpeneRow('{{$terpeneCounter}}')" value="-"/>
                                                    </td>
                                                </tr>
                                            @empty

                                            @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" role="tabpanel" class="tab-pane fade in active" id="additionalImages">
                                        <div class="col-md-12 text-right">
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <th  colspan="3" class="text-right">       
                                                    <input type="button" class="btn btn-primary" id="addMoreBtn" value="Add More Images" onclick="addMorePictures(1)"/>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Product Image</th>
                                                <th>Select Image</th>
                                            </tr>
                                            <tbody id="add_more">
                                            @php
                                                $counter = 0;
                                            @endphp
                                            @forelse($product->product_images as $product_image)
                                                @php
                                                    $counter++;
                                                @endphp
                                                    <tr id="row_{{$counter}}">
                                                        <td class="col-md-2">
                                                            <img src="{{ productImage(@$product_image->product_images) }}" alt="" id="img_{{$counter}}" style="height: 150px;width: 150px;">
                                                            <input type="hidden" name="saved_images[]" value="{{$product_image->id ?? ''}}">
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

                                                            <input type="button" class="btn btn-danger btn-md" id="removeMoreBtn" onclick="removeImgRow('{{$counter}}')" value="-"/>
                                                            
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
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
                                            @php $relatedProdCounter = 0; @endphp
                                            @foreach($relatedProducts as $rProduct)
                                                @php $relatedProdCounter++; @endphp
                                                <tr id="row_related_{{$relatedProdCounter}}" class="row_related_prod">
                                                    <td>
                                                        <select id="related_prod_id_{{$relatedProdCounter}}" class="form-control related_prod" name="related_prod_id[]" required>
                                                            <option value="{{$rProduct->related_id}}">{{ $rProduct->products[0]->product_name }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeRelatedProdRow({{$relatedProdCounter}})"></td>
                                                </tr>
                                            @endforeach
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
                                            @php $pProdCounter = 0; @endphp
                                            @foreach($promotionProducts as $pProduct)
                                                @php $pProdCounter++; @endphp
                                                <tr id="row_pp_table_{{$pProdCounter}}" class="row_pp_table">
                                                    <td>
                                                        <select id="row_pp_table_id_{{$pProdCounter}}" class="form-control promotion_products" name="promotion_products[]" required>
                                                            <option value="{{$pProduct->promotion_id}}">{{ $pProduct->promotedProducts[0]->product_name }}</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" step="0.01" class="form-control numberField price" name="promotion_products_price[]" value="{{$pProduct->price}}" required></td>
                                                    <td><input type="button" class="btn btn-danger btn-md" value="-" onclick="removeProdPromotionRow({{$pProdCounter}})"></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
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
    </div>
@endsection
@section('script')

    <script src="{{ asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/dropzone/dist/dropzone.js') }}"></script>
    <script type="text/javascript">
        window.onload = function () {
            CKEDITOR.replace('description', {
                {{--filebrowserUploadUrl: '{{ route('project.document-image-upload',['_token' => csrf_token() ]) }}',--}}
                {{--filebrowserUploadMethod: 'form'--}}
            });
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

        var counter = @if($counter) {{$counter}} @else 0 @endif;
        function addMorePictures(){
            counter++;
            $('#add_more').append(`<tr id="row_${counter}">
                                        <td class="col-md-4" >
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
                                        </td></tr>`);

        }

        function removeImgRow(counter){
            $('#row_'+counter).remove();
        }

        var terpeneCounter = @if($terpeneCounter) {{$terpeneCounter}} @else 0 @endif;
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

        //Dependent OPtion
        function getOptionValues(counter,val) {
            var option_id = val;
            if(option_id !== ''){
                $.ajax({
                    url:"{{ route('getOptionValues') }}",
                    type:"Get",
                    data: {
                        option_id: option_id
                    },
                    success:function (data) {
                        $('#option_value_id_'+counter).empty();
                        $.each(data.OptionValues,function(index,val){
                            $('#option_value_id_'+counter).append('<option value="'+val.id+'">'+val.option_value+'</option>');
                        })
                    }
                })
            }
        }

        /* Related product functions start */
        var counter2 = '{{++$relatedProdCounter}}';
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

        var counterPP = {{++$pProdCounter}};
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
