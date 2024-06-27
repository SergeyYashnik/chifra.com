@extends('layouts.main')

@section('title', "Настройка г. " . $city->name)

@section('content')
    @include('include.admin_menu')
    <h1>Редактирование города</h1>

    <form action="{{ route('admin.city.update', $city->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Название города</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $city->name }}" required>
        </div>
        <div class="form-check">
            <input type="checkbox" name="has_store" id="has_store" class="form-check-input" {{ $city->has_store ? 'checked' : '' }}>
            <label for="has_store" class="form-check-label">Есть магазин в этом городе</label>
        </div>
        <div class="form-group">
            <label for="delivery_price">Цена доставки (тг.)</label>
            <input type="number" name="delivery_price" id="delivery_price" class="form-control" value="{{ $city->delivery_price }}" required>
        </div>
        <div class="form-group">
            <label for="free_delivery_from">Бесплатная доставка от (тг.)</label>
            <input type="number" name="free_delivery_from" id="free_delivery_from" class="form-control" value="{{ $city->free_delivery_from }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Обновить город</button>
    </form>

@endsection
