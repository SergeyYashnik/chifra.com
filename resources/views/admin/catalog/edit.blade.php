@extends('layouts.main')

@section('title', "Редактирование категории")

@section('content')
    @include('include.admin_menu')
    @if($catalog->id_catalog != null)
        <a href="{{ route('admin.catalog.edit', ['id' => $catalog->id_catalog]) }}" class="btn btn-primary mt-3">Назад</a>
    @else
        <a href="{{ route('admin.catalog.index') }}" class="btn btn-primary mt-3">Назад</a>
    @endif
    <div class="container mt-5">
        <h2>Редактировать категорию</h2>
        <form action="{{ route('admin.catalog.update', ['id' => $catalog->id]) }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $catalog->name }}" required>
            </div>
            @if($catalog->id_catalog == null)
                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" name="image" id="image" class="form-control">
                    @if($catalog->image)
                        <div class="position-relative mt-2" style="width: 200px; height: 200px;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('storage/' . $catalog->image) }}" class="img-thumbnail" style="max-width: 100%; max-height: 100%; object-fit: cover;" alt="{{ $catalog->name }}">
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            <button type="submit" class="btn btn-primary mt-3">Сохранить изменения</button>
        </form>

        @if($catalog->image)
            <form action="{{ route('admin.catalog.removeImage', ['id' => $catalog->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm mt-3">
                    Удалить изображение
                </button>
            </form>
        @endif

        @if($subcatalogs->isEmpty())
            <a href="{{ route('admin.products.index', ['id_catalog' => $catalog->id]) }}" class="btn btn-primary mt-3">Настройки товара</a>

            <h3>Фильтры</h3>
            <form action="{{ route('admin.catalog.addFilter', ['id' => $catalog->id]) }}" method="POST" class="mb-5">
                @csrf
                <div class="form-group">
                    <label for="filter-name">Название фильтра</label>
                    <input type="text" name="name" id="filter-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="is_custom_input">Произвольный ввод</label>
                    <input type="checkbox" name="is_custom_input" id="is_custom_input" class="form-check-input">
                </div>
                <div class="form-group">
                    <label for="required_to_fill_out">Обязателен для заполнения</label>
                    <input type="checkbox" name="required_to_fill_out" id="required_to_fill_out" class="form-check-input">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Добавить фильтр</button>
            </form>
            @foreach($filters as $filter)
                <div class="row align-items-center mb-3 border p-3">
                    <div class="col-md-6">
                        <h5>{{ $filter->name }}</h5>
                        @if($filter->is_custom_input !== null)
                            <p>Произвольный ввод: {{ $filter->is_custom_input ? 'Да' : 'Нет' }}</p>
                        @endif
                        @if($filter->required_to_fill_out !== null)
                            <p>Обязателен для заполнения: {{ $filter->required_to_fill_out ? 'Да' : 'Нет' }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.filter.edit', ['id' => $filter->id]) }}" class="btn btn-warning btn-sm">Настройки</a>
                        <form action="{{ route('admin.filter.destroy', ['id' => $filter->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
        @if($filters->isEmpty() and $catalog->lvl < 3)
            <h3>Подкатегории</h3>
            <form action="{{ route('admin.catalog.addSubcatalog', ['id' => $catalog->id]) }}" method="POST" class="mb-5">
                @csrf
                <input type="hidden" name="lvl" value="{{ $catalog->lvl + 1 }}">
                <div class="form-group">
                    <label for="subcatalog-name">Название подкатегории</label>
                    <input type="text" name="name" id="subcatalog-name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Добавить подкатегорию</button>
            </form>
            @foreach($subcatalogs as $subcatalog)
                <div class="row align-items-center mb-3 border p-3">
                    <div class="col-md-2">
                        @if($subcatalog->image)
                            <img src="{{ asset('storage/' . $subcatalog->image) }}" class="img-fluid" alt="{{ $subcatalog->name }}">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5>{{ $subcatalog->name }}</h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('admin.catalog.edit', ['id' => $subcatalog->id]) }}" class="btn btn-warning btn-sm">Настройки</a>
                        <form action="{{ route('admin.catalog.destroy', ['id' => $subcatalog->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
