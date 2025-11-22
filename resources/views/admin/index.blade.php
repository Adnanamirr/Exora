
@extends('admin.dashboard')
@section('admin')



    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-page-head">
                @php

                    use App\Models\Plan;
                    use App\Models\User;
                    use App\Models\GeneratedContent;
                    use App\Models\Template;

                    $id = Auth::user()->id;
                    $user = User::with('plan')->find($id);
                    $totalDocument = GeneratedContent::where('user_id', $id)->count();
                    $userPlan = $user->plan;

                    $totalTemplate = Template::count();

                    $wordsUsed = $user->words_used ?? 0;
                    $wordLimit = $userPlan ? $userPlan->monthly_word_limit : 1000;

                @endphp


                <div class="nk-block-head-between">
                    <div class="nk-block-head-content">
                        <h2 class="display-6">Welcome {{ $user->name }}!</h2>
                    </div>
                </div>
            </div><!-- .nk-page-head -->
            <div class="nk-block">
                <div class="row g-gs">
                    <div class="col-sm-6 col-xxl-3">
                        <div class="card card-full bg-purple bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="fs-6 text-light mb-0">Words Available</div>
                                </div>
                                <h5 class="fs-1">  <small class="fs-3"> Total Words</small></h5>

                                <div class="caption-text">{{ $wordsUsed }} <span class="text-light">of {{ $wordLimit }} words used.</span></div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-sm-6 col-xxl-3">
                        <div class="card card-full bg-indigo bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="fs-6 text-light mb-0">Documents Available</div>
                                    <a href="{{ route('admin.document') }}" class="link link-indigo">See All</a>
                                </div>
                                <h5 class="fs-1">{{$totalDocument}} <small class="fs-3">Documents</small></h5>
                                <div class="fs-7 text-light mt-1">Total <span class="text-dark">{{$totalDocument}}</span> documents created</div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-sm-6 col-xxl-3">
                        <div class="card card-full bg-cyan bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="fs-6 text-light mb-0">Templates Available</div>
                                    <a href="{{route('admin.template') }}" class="link link-cyan">View All</a>
                                </div>
                                <h5 class="fs-1">12 <small class="fs-3">Templates</small></h5>
                                <div class="fs-7 text-light mt-1"><span class="text-dark">{{$totalTemplate}}</span> AI Content generation templates available</div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                </div><!-- .row -->
            </div><!-- .nk-block -->
            <div class="nk-block-head">
                <div class="nk-block-head-between">
                    <div class="nk-block-head-content">
                        <h2 class="display-6">Popular Templates</h2>
                    </div>
                    <div class="nk-block-head-content">
                        <a href="{{route('admin.template')}}" class="link">Explore All</a>
                    </div>
                </div>
            </div><!-- .nk-block-head -->
            <div class="nk-block">

                <div class="row g-gs filter-container" data-animation="true">

                    @foreach ($templates as $item)
                        <div class="col-sm-6 col-xxl-3 filter-item blog-content" data-category="blog-content">
                            <div class="card card-full shadow-none">
                                <div class="card-body">
                                    <a href="{{ route('user.details.template',$item->id) }}">
                                        <div class="media media-rg media-middle media-circle text-primary bg-primary bg-opacity-20 mb-3">
                                            <em class="{{ $item->icon }}"></em>
                                        </div>

                                        <h5 class="fs-4 fw-medium">{{ $item->title }}</h5>
                                        <p class="small text-light line-clamp-2">{{ $item->description }}</p>
                                    </a>

                                </div>
                            </div><!-- .card -->
                        </div><!-- .col -->
                    @endforeach



                </div><!-- .row -->
            </div><!-- .nk-block -->

            <div class="nk-block">

            </div><!-- .nk-block -->
        </div>
    </div>

@endsection
