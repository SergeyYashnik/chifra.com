@extends('layouts.main')

@section('title', "Оплата заказа")

@section('content')
    <h1>Оплата заказа</h1>
    <p>Общая сумма: {{ $totalAmount }}</p>

    <form action="{{ route('order.processPayment') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $orderId }}">
        <button type="submit" class="btn btn-primary">Оплатить</button>
    </form>
@endsection
