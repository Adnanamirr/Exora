@extends('admin.dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .nk-editor{
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
    </style>
    <div class="nk-content">
        <div class="container-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title">{{$template->title}}</h3>
                                    <p> {{$template->description }}</p>

                            </div>
                        </div>

                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class = "mb-3">
                                            <p> Your Balance <span class="badge bg-light text-dark ">{{ $user->current_word_usage - $user->words_used }}</span> words left </p>


                                            <form id="generateForm" action="{{ route('store.template', $template->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="form-group">
                                                    <label class=form-label for="language">Language</label>

                                                    <div class="form-control-wrap">
                                                        <select class=" form-select"
                                                                id="category" name="language">
                                                            <option
                                                                value="english" selected>English (USA)</option>
                                                            <option
                                                                value="Urdu" >Urdu (PAK)</option>
                                                            <option
                                                                value="Hindi" >Hindi (INDIA)</option>
                                                            <option
                                                                value="japanese" >Japanese (JAPAN)</option>
                                                            <option
                                                                value="turkish" >Turkish (TURKEY)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                @foreach($template->inputFields as $field  )
                                                    <div class="form-group mt-3">

                                                        <label for="{{$field->title}}">{{$field->title}}</label>
                                                        @if($field->type === 'text')

                                                            <input type="text" class="form-control" name="{{str_replace( ' ', '_', $field->title )}}" id="{{$field->title}}"  required>

                                                        @elseif($field->type === 'textarea')
                                                            <textarea class="form-control" name="{{str_replace( ' ', '_', $field->title )}}" id="{{$field->title}}" rows="4" required></textarea>
                                                        @endif
                                                        <small class="form-text text-muted">{{$field->description}}</small>


                                                    </div>
                                                @endforeach

                                                <div class="form-group mt-3">
                                                    <label class=form-label for="ai_model">AI Model</label>

                                                    <div class="form-control-wrap">
                                                        <select class=" form-select"
                                                                id="ai_model" name="ai_model">
                                                            <option
                                                                value="GPT-4" selected>OpenAI | GPT-4</option>
                                                            <option
                                                                value="GPT-3.5-turbo" >OpenAI | GPT-3.5-turbo</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="result_length ">Estimated Result length </label>
                                                            <div
                                                                class="form-control-wrap ">
                                                                <input type="number" class="form-control" name="result_length" id="result_length" value="200" min="50" max="1000" required>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-12 mt-3">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Generate
                                                        </button>
                                                    </div>
                                                </div>



                                            </form>

                                        </div>

                                    </div>

                                    {{--                                        end left side --}}
                                    <div class="col-md-8">

                                        <div class="nk-editor">
                                            <div class="nk-editor-header">
                                                <div class="nk-editor-title">
                                                    <h4 class="me-3 mb-0 line-clamp-1">{{$template->title}}</h4>
                                                    <ul class="d-inline-flex align-item-center">

                                                        <li>
                                                            <button class="btn btn-sm btn-icon btn-zoom">
                                                                <em class="icon ni ni-star"></em>
                                                            </button>
                                                        </li>
                                                        <li class="d-xl-none me-n1">
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-icon btn-zoom" type="button" data-bs-toggle="dropdown">
                                                                    <em class="icon ni ni-menu-alt-r"></em>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                    <div class="dropdown-content">
                                                                        <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                            <li>
                                                                                <a href="#"><em class="icon ni ni-file-docs"></em><span>Docs</span></a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#"><em class="icon ni ni-file-text"></em><span>Text</span></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="nk-editor-tools d-none d-xl-flex">
                                                    <ul class="d-inline-flex gap gx-3 gx-lg-4 pe-4 pe-lg-5">
                                                        <li>
                                                            <span class="sub-text text-nowrap" id="words_count">Words <span class="text-dark">25</span></span>
                                                        </li>
                                                        <li>
                                                            <span class="sub-text text-nowrap" id="char_count ">Characters <span class="text-dark">84</span></span>
                                                        </li>
                                                    </ul>
                                                    <ul class="d-inline-flex gap gx-3">
                                                        <li>
                                                            <div class="dropdown">
                                                                <button class="btn btn-md btn-light rounded-pill" type="button" data-bs-toggle="dropdown">
                                                                    <span>Export</span>
                                                                    <em class="icon ni ni-chevron-down"></em>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                    <div class="dropdown-content">
                                                                        <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                            <li>
                                                                                <a href="#" id="copy_text"><em class="icon ni ni-file-doc"></em><span>Copy Text</span></a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#" ><em class="icon ni ni-file-text"></em><span>Text</span></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <button class="btn btn-md btn-primary rounded-pill" type="button"> Save </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="nk-editor-main">

                                                <div class="nk-editor-body">
                                                    <div class="wide-md h-100">
                                                        <div class="js-editor nk-editor-style-clean nk-editor-full" data-menubar="false" id="editor-v1"></div> <!-- .js-editor -->
                                                    </div>
                                                </div><!-- .nk-editor-body -->
                                            </div><!-- .nk-editor-main -->
                                        </div><!-- .nk-editor -->


                                    </div>
                                    {{--                                        end right side--}}
                                </div>


                            </div>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
