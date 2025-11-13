@extends('admin.dashboard')
@section('admin')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Plan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('update.plan', $plan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 gx-gs">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">Plan Name</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ $plan->name }}"
                                           placeholder="Enter plan name">
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="monthly_word_limit">Monthly Word Limit</label>
                                    <input type="number"
                                           class="form-control @error('monthly_word_limit') is-invalid @enderror"
                                           id="monthly_word_limit" name="monthly_word_limit"
                                           value="{{ $plan->monthly_word_limit }}"
                                           placeholder="Enter monthly word limit">
                                    @error('word_limit')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="price">Price</label>
                                    <input type="number" step="0.01"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ $plan->price }}"
                                           placeholder="Enter price">
                                    @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="templates">Templates</label>
                                    <input type="text"
                                           class="form-control @error('templates') is-invalid @enderror"
                                           id="templates" name="templates"
                                           value="{{ $plan->templates }}"
                                           placeholder="Enter templates">
                                    @error('templates')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
