@extends('layouts.main')

@section('title', "Каталог")

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h3>Поиск товара</h3>
            <form method="GET" action="{{ route('catalog.show') }}" style="display: flex; ">
                <input type="text" id="search" name="search" class="form-control mr-2" style="width: 90%;"
                       placeholder="Введите название товара" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary" style="width: 8%;">Поиск</button>
            </form>
        </div>
        <div class="col-md-3">
            <div class="filters__filter">
                <div class="tree" style="font-size: 14px;">
                    <div class="tree__add-link">
                        <a href="{{ route('catalog.show') }}" class="tree__link">Все категории</a>
                        <span class="filters__count">({{ $count }})</span>
                    </div>
                    <ul class="tree__items">
                        @foreach($catalogs_lvl_1 as $catalog_lvl_1)
                            <li class="tree__item _expandable _expanded _active">
                                <a href="{{ route('catalog.show', ['catalog_lvl_1' => $catalog_lvl_1['name']]) }}"
                                   class="tree__link">{{ $catalog_lvl_1['name'] }}</a>
                                <span class="filters__count">({{ $catalog_lvl_1->count_1_lvl }})</span>
                                @if($catalogs_lvl_2)
                                    <ul class="tree__items">
                                        @foreach($catalogs_lvl_2 as $catalog_lvl_2)
                                            <li class="tree__item _expandable">
                                                <a href="{{ route('catalog.show', [
                                                'catalog_lvl_1' => $catalog_lvl_1['name'],
                                                'catalog_lvl_2' => $catalog_lvl_2['name']]) }}"
                                                   class="tree__link">{{ $catalog_lvl_2['name'] }}</a>
                                                <span class="filters__count">({{ $catalog_lvl_2->count_2_lvl }})</span>
                                                @if($catalogs_lvl_3)
                                                    <ul class="tree__items">
                                                        @foreach($catalogs_lvl_3 as $catalog_lvl_3)
                                                            <li class="tree__item">
                                                                <a href="{{ route('catalog.show', [
                                                                'catalog_lvl_1' => $catalog_lvl_1['name'],
                                                                'catalog_lvl_2' => $catalog_lvl_2['name'],
                                                                'catalog_lvl_3' => $catalog_lvl_3['name']]) }}"
                                                                   class="tree__link">{{ $catalog_lvl_3['name'] }}</a>
                                                                <span class="filters__count">({{ $catalog_lvl_3->count_3_lvl }})</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if(($brands and !$brands->isEmpty()) or ($minPrice and $maxPrice) or ($filters and !$filters->isEmpty()))
                <form action="{{ route('catalog.show') }}" method="GET">
                    @if($requestCatalogLvl1)
                        <input type="hidden" name="catalog_lvl_1" value="{{ $catalog_lvl_1->name }}">
                        @if(isset($requestCatalogLvl2))
                            <input type="hidden" name="catalog_lvl_2" value="{{ $catalog_lvl_2->name }}">
                            @if(isset($requestCatalogLvl3))
                                <input type="hidden" name="catalog_lvl_3" value="{{ $catalog_lvl_3->name }}">
                            @endif
                        @endif
                    @endif


                    @if(!$brands->isEmpty())
                        <div class="filters__filter">
                            <span class="filters__filter-title"><strong>Бренд</strong></span>
                            <div class="filters__filter-wrapper">
                                @foreach($brands as $brand)
                                    <div class="filters__filter-row">

                                        <input type="checkbox"
                                               name="brands[{{ $brand->name }}]"
                                               value="{{ $brand->id }}"
                                               class="form__checkbox _small _check"
                                            {{ isset($requestBrands[$brand->name]) ? 'checked' : '' }}>

                                        <label for=""
                                               class="filters__filter-row__checkbox form__checkbox _small">{{ $brand->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($minPrice and $maxPrice)
                        <div class="filters__filter">
                            <span class="filters__filter-title"><strong>Цена</strong></span>
                            <div class="filters__filter-wrapper">
                                <div class="filters__filter-row">
                                    <label for="min_price" class="filters__filter-row__label">Мин:</label>
                                    <input type="number"
                                           name="min_price"
                                           id="min_price"
                                           class="form__input _small"
                                           value="{{ request('min_price') }}"
                                           placeholder="Мин: {{ $minPrice }}">
                                </div>
                                <div class="filters__filter-row">
                                    <label for="max_price" class="filters__filter-row__label">Макс:</label>
                                    <input type="number"
                                           name="max_price"
                                           id="max_price"
                                           class="form__input _small"
                                           value="{{ request('max_price') }}"
                                           placeholder="Макс: {{ $maxPrice }}">
                                </div>
                            </div>
                        </div>
                    @endif


                    @if($filters and !$filters->isEmpty())
                        @foreach($filters as $filter)
                            @if(!$filter->filterValue->isEmpty())
                                @if(!$filter->filterValue->isEmpty())
                                    <div class="filters__filter">
                                        <span class="filters__filter-title"><strong>{{ $filter->name }}</strong></span>
                                        <div class="filters__filter-wrapper">
                                            @foreach($filter->filterValue as $filterValue)
                                                <div class="filters__filter-row">

                                                    <input type="checkbox"
                                                           name="filter_id[{{ $filter->id }}][{{ $filterValue->value }}]"
                                                           value="{{ $filterValue->id }}"
                                                           class="form__checkbox _small _check" {{ isset($filter_id[$filter->id][$filterValue->value]) ? 'checked' : '' }}>

                                                    <label for=""
                                                           class="filters__filter-row__checkbox form__checkbox _small">{{ $filterValue->value }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            @elseif(!$filter->requiredSubfilters->isEmpty())
                                @foreach($filter->requiredSubfilters as $subfilter)
                                    @if(!$subfilter->filterValue->isEmpty())
                                        <div class="filters__filter">
                                        <span
                                            class="filters__filter-title"><strong>{{ $subfilter->name }}</strong></span>
                                            <div class="filters__filter-wrapper">
                                                @foreach($subfilter->filterValue as $filterValue)
                                                    <div class="filters__filter-row">

                                                        <input type="checkbox"
                                                               name="filter_id[{{ $subfilter->id }}][{{ $filterValue->value }}]"
                                                               value="{{ $filterValue->id }}"
                                                               class="form__checkbox _small _check" {{ isset($filter_id[$subfilter->id][$filterValue->value]) ? 'checked' : '' }}>

                                                        <label for=""
                                                               class="filters__filter-row__checkbox form__checkbox _small">{{ $filterValue->value }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    <button type="submit" class="btn btn-primary">Применить фильтры</button>
                    <a href="{{ route('catalog.show', [
                                'catalog_lvl_1' => $requestCatalogLvl1 ?? null,
                                'catalog_lvl_2' => $requestCatalogLvl2 ?? null,
                                'catalog_lvl_3' => $requestCatalogLvl3 ?? null,
                            ]) }}" class="btn btn-danger">Сбросить</a>
                </form>
            @endif


        </div>

        <div class="col-md-9">
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="{{ route('product.show', ['name' => $product->name, 'id' => $product->id]) }}">
                                @if($product->oneImage)
                                    <img src="{{ asset('storage/' . $product->oneImage->path) }}" class="card-img-top"
                                         alt="Изображение товара">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">Цена: {{ $product->price }}</p>

                                </div>
                                @auth
                                    <div class="card-body">
                                        @if($product->cartItem)
                                            <form action="{{ route('cart.delete') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-danger">Удалить из корзины</button>
                                            </form>
                                        @else
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-primary">Добавить в корзину
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endauth
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
