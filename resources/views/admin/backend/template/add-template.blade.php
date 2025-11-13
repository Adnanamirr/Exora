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
                                <h3 class="nk-block-title">Create Template</h3>
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
                                    <form method="post" action="{{route('store.template')}}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3 gx-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="title">Title</label>
                                                    <input type="text"
                                                           class="form-control @error('title') is-invalid @enderror"
                                                           id="title" name="title" placeholder="Enter template title">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="category">Category</label>
                                                    <input type="text"
                                                           class="form-control @error('category') is-invalid @enderror"
                                                           id="category" name="category" placeholder="Enter category">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="description">Description</label>
                                                    <textarea
                                                        class="form-control @error('description') is-invalid @enderror"
                                                        id="description" name="description" rows="3"
                                                        placeholder="Enter description"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="icon">Icon</label>
                                                    <input type="text" placeholder="i.e <i class='icon ni ni-laptop'></i>"
                                                           class="form-control @error('icon') is-invalid @enderror"
                                                           id="icon" name="icon">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           id="flexCheckChecked" name="is_active" checked>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Activate Template
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <dic id="input-fields">
                                                    <div class="row input-field-row">
                                                        <div class="col-md-3">

                                                            <div class="form-group">
                                                                <label for="TemplateFormControlInputText1"
                                                                       class="form-label">Input Field Title *</label>

                                                               <div class="form-control-wrap">
                                                                   <input type="text" name="description"
                                                                          id="TemplateFormControlInputText1" class="form-control"
                                                                   >
                                                               </div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </dic>
                                            </div>


                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Create Template
                                                    </button>
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
