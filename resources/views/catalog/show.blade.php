@extends('layouts.main')

@section('title', "Каталог")

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="filters__filter">
                <div class="tree">
                    <div class="tree__add-link">
                        <a href="{{ route('catalog.show') }}" class="tree__link">Все категории</a>
                        <span class="filters__count">(806265)</span>
                    </div>
                    <ul class="tree__items">

                        @foreach($catalogs_lvl_1 as $catalog_lvl_1)
                            <li class="tree__item _expandable _expanded _active">
                                <a href="{{ route('catalog.show', ['catalog_lvl_1' => $catalog_lvl_1['name']]) }}"
                                   class="tree__link">{{ $catalog_lvl_1['name'] }}</a>
                                <span class="filters__count">(64738)</span>

                                @if($catalogs_lvl_2)
                                    <ul class="tree__items">
                                        @foreach($catalogs_lvl_2 as $catalog_lvl_2)
                                            <li class="tree__item _expandable">
                                                <a href="{{ route('catalog.show',
                                                                           [
                                                                                'catalog_lvl_1' => $catalog_lvl_1['name'],
                                                                                'catalog_lvl_2' => $catalog_lvl_2['name']])
                                                                                 }}"
                                                   class="tree__link">{{ $catalog_lvl_2['name'] }}</a>
                                                <span class="filters__count">(16706)</span>


                                                @if($catalogs_lvl_3)
                                                    <ul class="tree__items">
                                                        @foreach($catalogs_lvl_3 as $catalog_lvl_3)
                                                            <li class="tree__item">
                                                                <a href="{{ route('catalog.show',
                                                                        [
                                                                            'catalog_lvl_1' => $catalog_lvl_1['name'],
                                                                            'catalog_lvl_2' => $catalog_lvl_2['name'],
                                                                            'catalog_lvl_3' => $catalog_lvl_3['name']

                                                                            ]) }}"
                                                                   class="tree__link">{{ $catalog_lvl_3['name'] }}</a>
                                                                <span class="filters__count">(2651)</span>
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
        </div>
        {{--        <div class="col-md-9">--}}
        {{--            <h1 class="my-4">Каталог</h1>--}}
        {{--            @foreach($catalogs_lvl_1 as $catalog)--}}
        {{--                <h5 class="card-title">{{ $catalog['name'] }}</h5>--}}
        {{--            @endforeach--}}
        {{--        </div>--}}
    </div>
@endsection

