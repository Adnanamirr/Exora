@extends('admin.dashboard')
@section('admin')
<div class="nk-content">
    <div class="container-xl">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-page-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <h2 class="display-6">Template Library</h2>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="d-flex gap gx-4">
                                <div class="">
                                    <ul class="d-flex gap gx-2">
                                        <li>
                                            <a href="{{ route('add.template') }}" class="btn btn-primary">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Add Template</span>
                                            </a>
                                        </li>
                                        <li>
                                        <a href="templates-list.html" class="btn btn-md btn-icon btn-outline-light"><em class="icon ni ni-view-list-wd"></em></a>
                                        </li>
                                        <li>
                                            <a href="templates.html" class="btn btn-md btn-icon btn-primary btn-soft"><em class="icon ni ni-grid-fill"></em></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="">
                                    <div class="form-control-wrap">
                                        <div class="form-control-icon start md text-light">
                                            <em class="icon ni ni-search"></em>
                                        </div>
                                        <input type="text" class="form-control form-control-md" placeholder="Search Template">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-page-head -->
                <div class="nk-block">
{{--                    <ul class="filter-button-group mb-4 pb-1">--}}
{{--                        <li><button class="filter-button active" type="button" data-filter="*"> All </button></li>--}}
{{--                        <li><button class="filter-button" type="button" data-filter=".blog-content"> Blog Content </button></li>--}}
{{--                        <li><button class="filter-button" type="button" data-filter=".social-media"> Social Media </button></li>--}}
{{--                        <li><button class="filter-button" type="button" data-filter=".website-copy-seo"> Website Copy &amp; SEO </button></li>--}}
{{--                    </ul>--}}

                    <div class="row g-gs filter-container" data-animation="true">
                        @foreach($templates as $item)
                            <div class="col-sm-6 col-lg-4 filter-item blog-content" data-category="blog-content">

                                    <div class="card card-full shadow-none hover-shadow transition-shadow">
                                        <div class="card-body">
                                            <a href="{{route('details.template', $item->id)}}" class="text-decoration-none">
                                            <div
                                                class="media media-rg media-middle media-circle text-primary bg-primary bg-opacity-20 mb-3">
                                                <em class="{{$item->icon}}"></em>
                                            </div>
                                            </a>
                                            <a href="{{route('edit.template' , $item->id)}}" class="text-decoration-none">
                                            <h5 class="fs-4 fw-medium">{{$item->title}}</h5>
                                            <p class="small text-light line-clamp-2">{{$item->description}}</p>
                                            </a>
                                        </div>
                                    </div><!-- .card -->

                            </div><!-- .col -->
                        @endforeach
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div><!-- .nk-content-body -->
        </div><!-- .nk-content-inner -->
    </div><!-- .container-xl -->
</div><!-- .nk-content -->
@endsection
