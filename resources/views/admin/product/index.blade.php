@extends('layouts.main')

@section('title', "Редактирование товаров")

@section('content')
    @include('include.admin_menu')
    <div class="container mt-4">
        <h1>Редактирование товаров</h1>
        <a href="{{ route('admin.products.create', ['id_catalog' => $id_catalog]) }}" class="btn btn-primary">Добавить товар</a>
    </div>

    @foreach($filters as $filter)
        <b>
            {{ $filter->name }}
        </b>
        <br>
        @foreach($filter->subfilters as $subfilter)
            {{ $subfilter->name }}
            <br>
        @endforeach

    @endforeach

    <div class="row">
        @foreach($products as $product)
            @if($product->oneImage)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $product->oneImage->path) }}" class="card-img-top" alt="Изображение товара">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Цена: {{ $product->price }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>


@endsection
