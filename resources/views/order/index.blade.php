@extends('layouts.main')

@section('title', "Оформление заказа")

@section('content')
    <h1>Оформление заказа</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Название товара</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        @php
            $total = 0;
        @endphp
        @foreach($products as $product)
            @php
                $cartItem = $cartItems->firstWhere('id_product', $product->id);
                $subtotal = $product->price * $cartItem->quantity;
                $total += $subtotal;
            @endphp
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $cartItem->quantity }}</td>
                <td>{{ $subtotal }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">Итого</th>
            <th>{{ $total }}</th>
        </tr>
        </tfoot>
    </table>

    <h2>Адреса доставки</h2>
    @if ($user->addresses->isEmpty())
        <p>У вас пока нет сохраненных адресов доставки. Зайдите <a href="{{ route('profile') }}">в профиль</a> и добавьте адресы</p>
    @else
        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <div class="form-group">
                @foreach ($user->addresses as $index => $address)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="address_id" id="address_{{ $address->id }}" value="{{ $address->id }}" {{ $index == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="address_{{ $address->id }}">
                            <strong>Город:</strong> {{ $address->city->name }},
                            <strong>Улица:</strong> {{ $address->street }},
                            <strong>Дом:</strong> {{ $address->house }},
                            @if ($address->apartment)
                                <strong>Квартира:</strong> {{ $address->apartment }}
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-3">Оформить заказ</button>
        </form>
    @endif

@endsection
