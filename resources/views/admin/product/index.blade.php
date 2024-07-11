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
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->path) }}" class="card-img-top" alt="Изображение товара">
                    @else
                        <img src="{{ asset('storage/default.jpg') }}" class="card-img-top" alt="Изображение товара">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">Цена: {{ $product->price }}</p>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



@endsection
