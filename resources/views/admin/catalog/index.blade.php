@extends('layouts.main')

@section('title', "Настройки сайта")

@section('content')
    <div class="container mt-5">
        <a href="{{ route('admin.brand.index') }}" class="btn btn-primary mt-3">Настройки брендов</a>

        <h2>Добавить новую категорию</h2>
        <form action="{{ route('admin.catalog.store') }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="image">Изображение</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Добавить</button>
        </form>

        @foreach($catalogs as $catalog)
            <div class="row align-items-center mb-3 border p-3">
                <div class="col-md-2">
                    @if($catalog->image)
                        <img src="{{ asset('storage/' . $catalog->image) }}" class="img-fluid" alt="{{ $catalog->name }}">
                    @endif
                </div>
                <div class="col-md-6">
                    <h5>{{ $catalog->name }}</h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.catalog.edit', ['id' => $catalog->id]) }}" class="btn btn-warning btn-sm">Настройки</a>
                    <form action="{{ route('admin.catalog.destroy', ['id' => $catalog->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
