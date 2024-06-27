@extends('layouts.main')

@section('title', "Настройка городов")

@section('content')
    @include('include.admin_menu')
    <h1>Управление городами</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Добавить новый город</h2>
    <form action="{{ route('admin.city.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Название города</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-check">
            <input type="checkbox" name="has_store" id="has_store" class="form-check-input">
            <label for="has_store" class="form-check-label">Есть магазин в этом городе</label>
        </div>
        <div class="form-group">
            <label for="delivery_price">Цена доставки (тг.)</label>
            <input type="number" name="delivery_price" id="delivery_price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="free_delivery_from">Бесплатная доставка от (тг.)</label>
            <input type="number" name="free_delivery_from" id="free_delivery_from" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Добавить город</button>
    </form>

    <hr>

    <h2>Существующие города</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Магазин</th>
            <th>Цена доставки</th>
            <th>Бесплатная доставка от</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cities as $city)
            <tr>
                <td>{{ $city->name }}</td>
                <td>{{ $city->has_store ? 'Да' : 'Нет' }}</td>
                <td>{{ $city->delivery_price }}</td>
                <td>{{ $city->free_delivery_from }}</td>
                <td>
                    <a href="{{ route('admin.city.edit', $city->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                    <form action="{{ route('admin.city.delete', $city->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
