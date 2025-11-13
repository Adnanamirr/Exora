@extends('admin.dashboard')

@section('admin')
    <div class="nk-content">
        <div class="container-xl">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-page-head">
                        <div class="nk-block-head-between flex-wrap gap g-2">
                            <div class="nk-block-head-content">
                                <h2 class="display-6">All Plans</h2>

                            </div>
                            <div class="nk-block-head-content">
                                <ul class="nk-block-tools">
                                    <li><a class="btn btn-primary" href="{{route('add.plan')}}"><em
                                                class="icon ni ni-plus"></em><span>Add Plans</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .nk-page-head -->
                    <div class="d-flex align-items-center justify-content-between border-bottom border-light mt-5 mb-4 pb-2">
                        <h5>All Plans</h5>
                    </div>
                    <div class="card">
                        <table class="table table-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="tb-col">
                                    <div class="fs-13px text-base">Sl</div>
                                </th>
                                <th class="tb-col tb-col-md">
                                    <div class="fs-13px text-base">Name</div>
                                </th>
                                <th class="tb-col tb-col-sm">
                                    <div class="fs-13px text-base">Monthly Word Limit </div>
                                </th>
                                <th class="tb-col tb-col-sm">
                                    <div class="fs-13px text-base">Price</div>
                                </th>
                                <th class="tb-col tb-col-sm">
                                    <div class="fs-13px text-base">Template</div>
                                </th>
                                <th class="tb-col tb-col-sm">
                                    <div class="fs-13px text-base">Action</div>
                                </th>
                                <th class="tb-col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($plan as $key => $item)
                                <tr>
                                    <td class="tb-col">
                                        <div class="caption-text">{{$key+1}}
                                            <div class="d-sm-none dot bg-success"></div>
                                        </div>
                                    </td>
                                    <td class="tb-col tb-col-md">
                                        <div class="fs-6 text-light d-inline-flex flex-wrap gap gx-2">
                                            <span>{{$item->name}}</span></div>
                                    </td>
                                    <td class="tb-col tb-col-md">
                                        <div class="fs-6 text-light">{{$item->monthly_word_limit}} / Per Month</div>
                                    </td>
                                    <td class="tb-col tb-col-md">
                                        <div
                                            class="badge text-bg-success-soft rounded-pill px-2 py-1 fs-6 lh-sm">{{$item->price }}</div>
                                    </td>
                                    <td class="tb-col tb-col-md">
                                        <div
                                            class="badge text-bg-success-soft rounded-pill px-2 py-1 fs-6 lh-sm">{{$item->templates }}</div>
                                    </td>
                                    <td class="tb-col tb-col-md">
                                        <div class="d-flex gap-1">
                                            <a href="{{route('edit.plan',$item->id)}}" class="btn btn-sm btn-primary"><em class="icon ni ni-edit"></em></a>
                                            <form action="{{ route('delete.plan', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><em class="icon ni ni-trash"></em></button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
