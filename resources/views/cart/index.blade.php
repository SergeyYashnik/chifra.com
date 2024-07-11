@extends('layouts.main')

@section('title', "Корзина")

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Корзина</h1>
        @foreach($products as $product)
            @php
                $cartItem = $cartItems->firstWhere('id_product', $product->id);
                $totalPrice = $product->price * $cartItem->quantity;
            @endphp
            <div class="card mb-4 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4 d-flex align-items-center justify-content-center p-3">
                        @if($product->oneImage)
                            <img src="{{ asset('storage/' . $product->oneImage->path) }}" class="img-fluid" alt="Изображение товара">
                        @else
                            <img src="https://via.placeholder.com/150" class="img-fluid" alt="Нет изображения">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">Цена: {{ $product->price }} руб.</p>
                            <p class="card-text">Общая стоимость: {{ $totalPrice }} руб.</p>
                            <form action="{{ route('cart.updateQuantity') }}" method="POST" class="d-inline-block">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="input-group mb-3" style="max-width: 200px;">
                                    <input type="number" class="form-control" name="quantity" value="{{ $cartItem->quantity }}" min="1">
                                    <button type="submit" class="btn btn-outline-secondary">Обновить</button>
                                </div>
                            </form>
                            <form action="{{ route('cart.delete') }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @if(!$products->isEmpty())
            <div class="text-right mt-4">
                <a href="{{ route('order') }}" class="btn btn-success">Купить</a>
            </div>
        @else
            <p>Ваша корзина пуста</p>
        @endif

    </div>
@endsection
