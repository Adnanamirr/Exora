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
                                <h3 class="nk-block-title">Edit Template</h3>
                            </div>
                        </div>
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
                                <form method="post" action="{{ route('update.template', $template->id) }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3 gx-gs">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="title">Title</label>
                                                <input type="text"
                                                       class="form-control @error('title') is-invalid @enderror"
                                                       id="title" name="title" placeholder="Enter template title"
                                                       value="{{ old('title', $template->title) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="category">Category</label>
                                                <select class="form-select @error('category') is-invalid @enderror"
                                                        id="category" name="category">
                                                    <option value="">Select Category</option>
                                                    <option
                                                        value="ads" {{ old('category', $template->category) == 'ads' ? 'selected' : '' }}>
                                                        Ads
                                                    </option>
                                                    <option
                                                        value="articles" {{ old('category', $template->category) == 'articles' ? 'selected' : '' }}>
                                                        Articles and Contents
                                                    </option>
                                                    <option
                                                        value="blog_post" {{ old('category', $template->category) == 'blog_post' ? 'selected' : '' }}>
                                                        Blog Post
                                                    </option>
                                                    <option
                                                        value="ecommerce" {{ old('category', $template->category) == 'ecommerce' ? 'selected' : '' }}>
                                                        Ecommerce
                                                    </option>
                                                    <option
                                                        value="website" {{ old('category', $template->category) == 'website' ? 'selected' : '' }}>
                                                        Website
                                                    </option>
                                                     <option
                                                        value="social_media" {{ old('category', $template->category) == 'social_media' ? 'selected' : '' }}>
                                                        Social Media
                                                    </option>
                                                    <option
                                                        value="marketing" {{ old('category', $template->category) == 'marketing' ? 'selected' : '' }}>
                                                        Marketing
                                                    </option>
                                                    <option
                                                        value="email" {{ old('category', $template->category) == 'email' ? 'selected' : '' }}>
                                                        Email
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="description">Description</label>
                                                <textarea
                                                    class="form-control @error('description') is-invalid @enderror"
                                                    id="description" name="description" rows="3"
                                                    placeholder="Enter description">{{ old('description', $template->description) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="icon">Icon</label>
                                                <input type="text" placeholder="i.e <i class='icon ni ni-laptop'></i>"
                                                       class="form-control @error('icon') is-invalid @enderror"
                                                       id="icon" name="icon" value="{{ old('icon', $template->icon) }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div id="input-fields">
                                                @php
                                                    // Use old input if validation failed, otherwise use the first existing field (if any)
                                                    $existingField = isset($template) && $template->inputFields->count() ? $template->inputFields->first() : null;
                                                @endphp

                                                <div class="row input-field-row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="input_fields_0_title" class="form-label">Input Field Title *</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" name="input_fields[0][title]"
                                                                       id="input_fields_0_title"
                                                                       placeholder="Enter Input Field Title"
                                                                       class="form-control"
                                                                       value="{{ old('input_fields.0.title', optional($existingField)->title ?? '') }}"
                                                                       required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="input_fields_0_description" class="form-label">Input Field Description *</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" name="input_fields[0][description]"
                                                                       id="input_fields_0_description"
                                                                       placeholder="Enter Input Field Description"
                                                                       class="form-control"
                                                                       value="{{ old('input_fields.0.description', optional($existingField)->description ?? '') }}"
                                                                       required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="input_fields_0_type" class="form-label">Field Type</label>
                                                            <div class="form-control-wrap">
                                                                <select name="input_fields[0][type]"
                                                                        id="input_fields_0_type" class="form-select" required>
                                                                    <option value="text" {{ old('input_fields.0.type', optional($existingField)->type ?? 'text') == 'text' ? 'selected' : '' }}>Input Field</option>
                                                                    <option value="textarea" {{ old('input_fields.0.type', optional($existingField)->type ?? '') == 'textarea' ? 'selected' : '' }}>Textarea Field</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-label"></label>
                                                            <div class="form-control-wrap">
                                                                <input type="hidden" name="input_fields[0][is_required]"
                                                                       value="{{ old('input_fields.0.is_required', optional($existingField)->is_required ?? '1') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                        <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                       id="flexCheckChecked" name="is_active"
                                                    {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    Activate Template
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="prompt">Custom Prompt *</label>
                                                <textarea class="form-control @error('prompt') is-invalid @enderror"
                                                          id="prompt" name="prompt" rows="5"
                                                          placeholder="Enter prompt">{{ old('prompt', $template->prompt) }}</textarea>
                                                <small class="form-text text-muted">Write a 400 word article about
                                                    {topic} with an introduction .</small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update Template</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
