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
                                <h3 class="nk-block-title">Add New Plan</h3>
                            </div>
                        </div><!-- .nk-block-head -->
                        <div class="card shadow-none">
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                @endif
                                <form method="post" action="{{route('add.plan.store')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3 gx-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Plan Name</label>
                                                <input type="text"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       id="name" name="name" placeholder="Enter plan name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="monthly_word_limit">Monthly Word
                                                    Limit</label>
                                                <input type="number"
                                                       class="form-control @error('monthly_word_limit') is-invalid @enderror"
                                                       id="monthly_word_limit" name="monthly_word_limit"
                                                       placeholder="Enter monthly word limit">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="price">Price</label>
                                                <input type="number" step="0.01"
                                                       class="form-control @error('price') is-invalid @enderror"
                                                       id="price" name="price" placeholder="Enter price">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="templates">Templates</label>
                                                <input type="number"
                                                       class="form-control @error('templates') is-invalid @enderror"
                                                       id="templates" name="templates" placeholder="Enter templates">
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Add Plan</button>
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
