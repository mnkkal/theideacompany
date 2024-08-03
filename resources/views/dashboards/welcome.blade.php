@extends('layouts.app')

@section('title')
    {{ __('Products') }}
@endsection

@section('page_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dataTable.min.css') }}"/>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ mix('assets/css/admin_panel.css') }}">
@endsection
<style>.img-400x400 {
    width: 400px;
    height: 400px;
    object-fit: cover; /* This ensures the image covers the box without distortion */
}
</style>
@section('content')
    <div class="container-fluid page__container">
        <div class="animated fadeIn main-table">
            @include('flash::message')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header page-header flex-wrap align-items-sm-center align-items-start flex-sm-row flex-column">
                            <div class="user-header d-flex align-items-center justify-content-between">
                                <div class="pull-left page__heading me-3 my-2">
                                    {{ __('Products') }}
                                </div>
                            </div>
                            <div class="filter-container user-filter align-self-sm-center align-self-end ms-auto">
                                <div class="me-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Filter content here -->
                                </div>
                                <div class="me-sm-2 my-2 user-select2 ms-sm-0 ms-auto">
                                    <!-- Filter content here -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($products as $product)
                                    <div class="col-md-6">
                                        <div class="product">
                                        <img src="{{ $product->image ? asset('images/' . $product->image) : 'https://via.placeholder.com/400x400' }}" alt="{{ $product->name }}" class="img-fluid" style="width: 400px; height: 400px;">
                                        <h2>{{ $product->name }}</h2>

                                            <p>
                                            {{ Str::limit($product->description, 100, '...') }}
                                            </p>
                                            <p><strong>Rs. {{ $product->price }}</strong></p>
                                            <a href="{{ route('payment', $product->id) }}" class="btn btn-primary">Buy Now</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="pull-right me-3">
                                <!-- Pagination or other content here -->
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
