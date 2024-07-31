@extends('layouts.app')
@section('title')
    {{ __('Jobs') }}
@endsection
@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div
                            class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('Edit Job') }}
                                </div>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{route('subscriptions.update', $subscription->id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{$subscription->id}}">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="live-preview">
                                                        <div class="row gy-4">
                                                            <div class="col-xxl-6 col-md-6">
                                                                <div>
                                                                    <label for="nameInput" class="form-label">Name <span style="color:red">*</span></label>
                                                                    <input type="text" class="form-control" name="name" value="{{ $subscription->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-xxl-6 col-md-6">
                                                                <div>
                                                                    <label for="priceInput" class="form-label">Price <span style="color:red">*</span></label>
                                                                    <input type="number" class="form-control" name="price" step="any" value="{{ $subscription->price }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-xxl-12 col-md-12">
                                                                <div>
                                                                    <label for="descriptionInput" class="form-label">Description <span style="color:red">*</span></label>
                                                                    <textarea class="form-control" name="description" required>{{ $subscription->description }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-xxl-12 col-md-12">
                                                                <div>
                                                                    <label for="imageInput" class="form-label">Image</label>
                                                                    <input type="file" class="form-control" name="image">
                                                                    @if ($subscription->image)
                                                                        <img src="{{ asset('images/' . $subscription->image) }}" alt="Current Image" style="max-width: 100px; margin-top: 10px;">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="chat_group">Chat Group:</label>
                                                                <div class="form-group row login-group__sub-title" id="chat_groupContainer">
                                                                    @foreach ($chat_group as $group)
                                                                        <div class="form-group col-sm-6 login-group__sub-title">
                                                                            <input type="checkbox" name="chat_group[]" value="{{ $group->id }}" id="chat_group_{{ $group->id }}"
                                                                            @if (isset($subscription->chat_group) && in_array($group->id, json_decode($subscription->chat_group))) checked @endif>
                                                                            <label for="chat_group_{{ $group->id }}">{{ $group->name }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="permissions">Permissions:</label>
                                                                <div class="form-group row login-group__sub-title" id="permissionsContainer">
                                                                    @foreach ($permissions as $permission)
                                                                        <div class="form-group col-sm-6 login-group__sub-title">
                                                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}"
                                                                            @if (isset($subscription->permissions) && in_array($permission->name, json_decode($subscription->permissions))) checked @endif>
                                                                            <label for="permission_{{ $permission->id }}">{{ $permission->display_name }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
@section('page_js')
@endsection
@section('scripts')
    <script>
        let defaultImageAvatar = "{{ getDefaultAvatar() }}"
    </script>
@endsection
