@extends('admin.dashboard')
@section('admin')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <div class="nk-content">
        <div class="container-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">

                    <div class="nk-block">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title">Change Password</h3>
                            </div>
                        </div><!-- .nk-block-head -->
                        <div class="card shadow-none">
                            <div class="card-body">
                                <form method="post" action="{{ route('admin.change.password') }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3 gx-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="old_password">Old Password</label>
                                                <input type="password"
                                                       class="form-control @error('old_password') is-invalid @enderror"
                                                       id="old_password" name="old_password"
                                                       placeholder="Enter Old Password">
                                                @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="new_password">New Password</label>
                                                <input type="password"
                                                       class="form-control @error('new_password') is-invalid @enderror"
                                                       id="new_password" name="new_password" placeholder="Enter New Password">
                                                @error('new_password')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="new_password_confirmation">Confirm
                                                    Password</label>
                                                <input type="password"
                                                       class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                                       id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm Password">
                                                @error('new_password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- .card-body -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->

                </div>
            </div>
        </div>
    </div>

@endsection
