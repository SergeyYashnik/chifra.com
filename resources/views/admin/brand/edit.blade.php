@extends('layouts.main')

@section('title', "Редактирование бренда")

@section('content')
    <div class="container">
        <h1 class="my-4">Редактирование бренда</h1>

        <!-- Форма для редактирования бренда -->
        <div class="mb-4">
            <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Название бренда</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Логотип бренда</label>
                    @if($brand->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" width="100">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>

        <!-- Форма для удаления логотипа -->
        @if($brand->logo)
            <div class="mb-4">
                <form action="{{ route('admin.brand.destroy-logo', $brand->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Удалить логотип</button>
                </form>
            </div>
        @endif
    </div>
@endsection
