@extends('admin.layout')
@section('title', (isset($content->id) ?  'Edit' : 'Add').' Staff')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Change Password</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
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
                                <h3 class="card-title">Change Admin Password</h3>
                            </div>
                            <form class="category-form" method="post" action="{{route('admin.changePassword')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if(Session::has('success'))
                                        <div class="alert alert-success">{{Session::get('success')}}</div>
                                    @endif

                                    <div class="form-group">
                                        <label for="oldPassword">Old Password</label>
                                        <input type="password" class="form-control @error('oldPassword') is-invalid @enderror" name="oldPassword" id="oldPassword" value="{{$content->oldPassword?? old('oldPassword')}}" placeholder="Old Password" required>
                                        @error('oldPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword" id="newPassword" value="{{$content->newPassword?? old('newPassword')}}" placeholder="New Password" >
                                        @error('newPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm Password</label>
                                        <input type="password" class="form-control @error('confirmPassword') is-invalid @enderror" name="confirmPassword" id="confirmPassword" value="{{$content->confirmPassword?? old('confirmPassword')}}" placeholder="Confirm Password" required>
                                        @error('confirmPassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    
                                                                         
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-md">Submit</button>
                                    {{-- <a href="{{route('admin.staffMember')}}" class="btn btn-warning btn-md">Cancel</a> --}}
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
        $(document).ready(function(){
            // toastr.error('Something Went Wrong!!');
            console.log();
            if($('#editCheck').is(":checked")){
                $('#editCheck').val('yes');
                $('#editPassword').fadeIn();
            }else{
                $('#editCheck').val('no');
                $('#editPassword').fadeOut();
            }
        });
        $('#editCheck').change(function(){
           let checkValue =   $('#editCheck').val();
           console.log('before'+checkValue);
           if(checkValue == 'no'){
                $('#editCheck').val('yes');
                $('#editPassword').fadeIn();
           }else{
                $('#editCheck').val('no');
                $('#editPassword').fadeOut();
           }
        });
    </script>
    @endsection
