@extends('layouts.main')

@section('title', "Бренды")

@section('content')
    <div class="container">
        <h1 class="my-4">Бренды</h1>

        <!-- Форма для добавления нового бренда -->
        <div class="mb-4">
            <h2>Добавить новый бренд</h2>
            <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Название бренда</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Логотип бренда</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Добавить</button>
            </form>
        </div>

        <!-- Таблица с брендами -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Логотип</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr>
                        <td>{{ $brand->name }}</td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" width="100">
                            @else
                                Нет логотипа
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.brand.edit', $brand->id) }}" class="btn btn-primary">Редактировать</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
