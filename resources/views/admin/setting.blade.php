@extends('admin.layout')
@section('title', 'Site Setting')
@section('css')
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
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
        border-radius: 0.25rem;
    }

    .slider.round:before {
        border-radius: 10%;
    }
</style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Site Setting</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Site Setting</li>
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
                                <h3 class="card-title">Site Setting</h3>
                            </div>
                            <form class="category-form" method="post" action="{{ route('admin.setting') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Site Title</label>
                                                <input type="text" class="form-control" name="title" id="name"
                                                       value="{{ $content->title ?? '' }}" placeholder="site title"
                                                       required>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                       value="{{ $content->email ?? '' }}" placeholder="email" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Phone </label>
                                                <input type="text" class="form-control" name="phone" id="name"
                                                    value="{{ $content->phone ?? '' }}" placeholder="Phone Number"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" name="address" id="address"
                                                       value="{{ $content->address ?? '' }}" placeholder="Address">
{{--                                                <textarea class="form-control" name="address" id="address" required>{{ $content->address ?? '' }}</textarea>--}}
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <div class="form-group">
                                                <label>Android App URL </label>
                                                <input type="text" class="form-control" name="android_app_url" id="android_app_url"
                                                       value="{{ $content->android_app_url ?? '' }}" placeholder="Android App URL">
                                            </div>
                                            <div class="form-group">
                                                <label>Android App Version </label>
                                                <input type="text" class="form-control" name="android_app_version" id="android_app_version"
                                                       value="{{ $content->android_app_version ?? '' }}" placeholder="Android App Version">
                                            </div>
                                            <div class="form-group">
                                                <label for="android_force_update" class="col-md-6">Android App Force Update </label>
                                                <label class="switch col-md-6">
                                                    <input type="checkbox" id="android_force_update" name="android_force_update" @if($content->android_force_update==1) checked @endif value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>QR Code Image for Mobile Apps</label>
                                                <div class="input-group-btn">
                                                    <div class="image-upload">
                                                        <img src="{{ isset($content->qr_code_image) ? $content->qr_code_image : 'admin/dist/img/placeholder.png' }}"
                                                             class="img-responsive" width="100px" height="100px" id="imgResponsive">
                                                        <div class="file-btn mt-4">
                                                            <input type="file" id="qrcodeimage" name="qr_code_image" accept="image/*">
                                                            <input type="text" id="qr_code_image" name="qr_code_image"
                                                                   value="{{ !empty($content->qr_code_image) ? $content->qr_code_image : '' }}"
                                                                   hidden="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Facebook</label>
                                                <input type="url" class="form-control" name="facebook" id="facebook"
                                                       value="{{ $content->facebook ?? '' }}" placeholder="Facebook">
                                            </div>
                                            <div class="form-group">
                                                <label>Twitter</label>
                                                <input type="text" class="form-control" name="twitter" id="twitter"
                                                       value="{{ $content->twitter ?? '' }}" placeholder="Twitter">
                                            </div>

                                            <div class="form-group">
                                                <label>Instagram</label>
                                                <input type="text" class="form-control" name="instagram" id="instagram"
                                                       value="{{ $content->instagram ?? '' }}" placeholder="Instagram">
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="currency">Currency Symbol</label>
                                                            <input type="text" class="form-control" name="currency" id="currency"
                                                                   value="{{ $content->currency ?? '' }}" placeholder="Currency">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>GST Tax </label>
                                                            <input type="text" step="0.1" class="form-control" name="tax" id="tax"
                                                                   value="{{ $content->tax ?? 0.00 }}" placeholder="Tax">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="dropdown-divider"></div>
                                            <div class="form-group">
                                                <label>IOS App URL </label>
                                                <input type="text" class="form-control" name="ios_app_url" id="ios_app_url"
                                                       value="{{ $content->ios_app_url ?? '' }}" placeholder="IOS App URL">
                                            </div>
                                            <div class="form-group">
                                                <label>IOS App Version </label>
                                                <input type="text" class="form-control" name="ios_app_version" id="ios_app_version"
                                                       value="{{ $content->ios_app_version ?? '' }}" placeholder="IOS App Version">
                                            </div>
                                            <div class="form-group">
                                                <label for="ios_force_update" class="col-md-6">IOS App Force Update </label>
                                                <label class="switch col-md-6">
                                                    <input type="checkbox" id="ios_force_update" name="ios_force_update" @if($content->ios_force_update==1) checked @endif value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Logo</label>
                                                <div class="input-group-btn">
                                                    <div class="image-upload">
                                                        <img src="{{ isset($content->logo) ? $content->logo : 'admin/dist/img/placeholder.png' }}"
                                                             class="img-responsive" width="100px" height="100px" id="img0">
                                                        <div class="file-btn mt-4">
                                                            <input type="file" id="logo" name="logo" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer float-sm-right">
                                        <button type="submit" onclick="validateinputs()" class="btn btn-primary">Submit</button>
                                    </div>
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
        $('#logo').on('change', function(){
            const [file] = logo.files
            if (file) {
                img0.src = URL.createObjectURL(file)
            }
        });

        $('#qrcodeimage').on('change', function(){
            const [file] = qrcodeimage.files
            if (file) {
                imgResponsive.src = URL.createObjectURL(file)
            }
        });
    </script>
@endsection
