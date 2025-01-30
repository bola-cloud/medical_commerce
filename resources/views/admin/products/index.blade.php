@extends('layouts.admin')

@section('content')
@if (app()->getLocale() === 'ar') <!-- Assuming 'ar' is your RTL language -->
<style>
    .carousel-control-prev {
        right: auto;
        left: 0;
    }

    .carousel-control-next {
        left: auto;
        right: 0;
    }

    .carousel-control-prev-icon {
        transform: scaleX(-1);
    }

    .carousel-control-next-icon {
        transform: scaleX(1);
    }
</style>
@endif

<div class="card p-3">
    <div class="card-header d-flex justify-content-between">
        <h1>{{ __('lang.product_list') }}</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">{{ __('lang.add_product') }}</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('lang.image') }}</th>
                <th>{{ __('lang.ar_name') }}</th>
                <th>{{ __('lang.en_name') }}</th>
                <th>{{ __('lang.category') }}</th>
                <th>{{ __('lang.price') }}</th>
                <th>{{ __('lang.quantity') }}</th>
                <th>{{ __('lang.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <!-- Primary Image -->
                    <td>
                        @php
                            // Decode the images column if it's a JSON string
                            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;

                            // Ensure $images is an array and contains data
                            if (is_array($images) && count($images) > 0) {
                                // Find the primary image
                                $primaryImage = collect($images)->firstWhere('primary', true);

                                // Fallback to the first image if no primary is set
                                if (!$primaryImage) {
                                    $primaryImage = $images[0];
                                }
                            } else {
                                $primaryImage = null;
                            }
                        @endphp

                        @if ($primaryImage && isset($primaryImage['url']))
                            <img src="{{ $primaryImage['url'] }}" alt="{{ $product->en_name }}" class="img-thumbnail fixed-height">
                        @else
                            <span>{{ __('lang.no_image') }}</span>
                        @endif
                    </td>

                    <td>{{ $product->ar_name }}</td>
                    <td>{{ $product->en_name }}</td>
                    <td>
                        {{ $product->category ? (app()->getLocale() == 'ar' ? $product->category->ar_name : $product->category->en_name) : __('lang.no_category') }}
                    </td>
                    <td>${{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#productImagesModal{{ $product->id }}">
                            {{ __('lang.view_images') }}
                        </button>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">{{ __('lang.edit') }}</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('lang.confirm_delete') }}')">{{ __('lang.delete') }}</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal for Images -->
                <div class="modal fade" id="productImagesModal{{ $product->id }}" tabindex="-1" aria-labelledby="productImagesModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productImagesModalLabel{{ $product->id }}">{{ __('lang.product_images') }}: {{ $product->ar_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('lang.close') }}"></button>
                            </div>
                            <div class="modal-body">
                                @if (is_array($images) && count($images) > 0)
                                    <div id="carousel{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($images as $index => $image)
                                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                    <img src="{{ $image['url'] }}" class="d-block w-100" alt="{{ __('lang.product_image') }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $product->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{ __('lang.previous') }}</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $product->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{ __('lang.next') }}</span>
                                        </button>
                                    </div>
                                @else
                                    <p>{{ __('lang.no_images') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>


    </table>
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .fixed-height {
        height: 50px;
        object-fit: cover;
    }

    .carousel-control-prev,
    .carousel-control-next {
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
