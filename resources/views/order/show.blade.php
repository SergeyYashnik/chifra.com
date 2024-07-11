@extends('layouts.main')

@section('title', "Просмотр заказов")

@section('content')
    <h1>Ваши оплаченные заказы</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Номер заказа</th>
            <th>Дата оформления заказа</th>
        </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->updated_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">У вас пока нет оплаченных заказов.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
