@extends('layouts.main')

@section('title', $product->name)

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">


                <div class="breadcrumbs">
                    <a href="{{ route('catalog.show') }}" class="breadcrumbs__item">Все товары</a>
                    @if($catalogs_lvl_1)
                        <span class="breadcrumbs__separator">&rarr;</span>
                        <a href="{{ route('catalog.show', ['catalog_lvl_1' => $catalogs_lvl_1->name]) }}"
                           class="breadcrumbs__item">{{ $catalogs_lvl_1->name }}</a>
                        @if($catalogs_lvl_2)
                            <span class="breadcrumbs__separator">&rarr;</span>
                            <a href="{{ route('catalog.show', [
                                            'catalog_lvl_1' => $catalogs_lvl_1->name,
                                            'catalog_lvl_2' => $catalogs_lvl_2->name]) }}"
                               class="breadcrumbs__item">{{ $catalogs_lvl_2->name }}</a>
                            @if($catalogs_lvl_3)
                                <span class="breadcrumbs__separator">&rarr;</span>
                                <a href="{{ route('catalog.show', [
                                                'catalog_lvl_1' => $catalogs_lvl_1->name,
                                                'catalog_lvl_2' => $catalogs_lvl_2->name,
                                                'catalog_lvl_3' => $catalogs_lvl_3->name]) }}"
                                   class="breadcrumbs__item">{{ $catalogs_lvl_3->name }}</a>
                            @endif
                        @endif
                    @endif
                    <span class="breadcrumbs__separator">&rarr;</span>
                    <a href="{{ route('product.show', ['id' => $product->id, 'name' => $product->name]) }}"
                       class="breadcrumbs__item">{{ $product->name }}</a>
                </div>



                <div class="swiper-container"
                     style="max-width: 100%; overflow: hidden; position: relative; height: {{ $maxHeight }}px;">
                    <div class="swiper-wrapper">
                        @foreach($product->images as $image)
                            <div class="swiper-slide" style="text-align: center;">
                                <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid"
                                     alt="{{ $product->name }}"
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"
                         style="position: absolute; top: 50%; transform: translateY(-50%); right: 0;"></div>
                    <div class="swiper-button-prev"
                         style="position: absolute; top: 50%; transform: translateY(-50%); left: 0;"></div>
                    <div class="swiper-pagination"></div>
                </div>


                <div class="mt-4 text-center">
                    <h2>{{ $product->name }}</h2>
                    <p class="lead">Цена: <strong>{{ $product->price }}</strong></p>
                    <p class="lead">Бренд: <strong>{{ $brand->name }}</strong></p>
                </div>
                <div class="mt-4">
                    <div class="row">
                        <div class="col">
                            @if($product->description)
                                <h2>Описание</h2>
                                <p>{{ $product->description }}</p>
                            @endif


                            @if($filters)
                                <h2>Характеристики</h2>
                                @foreach($filters as $filter)
                                    @if(!$filter->filterValue->isEmpty())
                                        <div class="specifications-list__spec">
                                            @if($filter->filterValue->first() != null)
                                                <div class="d-flex justify-content-between"
                                                     style="border-bottom: 2px solid #000;">
                                                    <span
                                                        class="specifications-list__spec-term">{{ $filter->name }}:</span>
                                                    <span
                                                        class="specifications-list__spec-definition text-end align-self-end">{{ $filter->filterValue->first()->value }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <br>
                                    @elseif(!$filter->subfilters->isEmpty())

                                        <div class="specifications-list__spec">
                                            <div class="d-flex justify-content-between"
                                                 style="border-bottom: 2px solid #000;">
                                                <span
                                                    class="specifications-list__spec-term"><strong>{{ $filter->name }}</strong></span>
                                            </div>


                                            @foreach($filter->subfilters as $subfilter)
                                                @if($subfilter->filterValue->first() != null)
                                                    <div class="d-flex justify-content-between"
                                                         style="border-bottom: 1px solid rgba(0,0,0,0.38);">
                                                        <span class="specifications-list__spec-term">{{ $subfilter->name }}:</span>
                                                        <span
                                                            class="specifications-list__spec-definition text-end align-self-end">{{ $subfilter->filterValue->first()->value }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <br>
                                    @endif

                                @endforeach
                            @endif


                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            slidesPerView: 1,
            centeredSlides: true,
        });
    </script>
@endsection
