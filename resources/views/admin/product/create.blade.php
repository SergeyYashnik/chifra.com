@extends('layouts.main')

@section('title', "Добавление товара")

@section('content')
    <div class="container mt-4">
        <h1>Добавление товара</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Название товара -->
            <div class="mb-3">
                <label for="name" class="form-label">Название товара</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <!-- Описание товара -->
            <div class="mb-3">
                <label for="description" class="form-label">Описание товара</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>

            <!-- Цена товара -->
            <div class="mb-3">
                <label for="price" class="form-label">Цена товара</label>
                <span class="text-danger">*</span>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>

            <!-- Бренд -->
            <div class="mb-3">
                <label for="brand" class="form-label">Бренд</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="brand" name="brand" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <input type="number" class="form-control" id="id_catalog" name="id_catalog" value="{{ $id_catalog }}" hidden>


            <!-- Количество товара на складе -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Количество товара на складе</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>

            <!-- Скидка -->
            <div class="mb-3">
                <label for="sale" class="form-label">Скидка (%)</label>
                <input type="number" class="form-control" id="sale" name="sale" min="0" max="100">
            </div>

            <!-- Изображения -->
            <div class="mb-3">
                <label for="images" class="form-label">Изображения</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
            </div>

            <!-- Фильтры -->
            <h3>Фильтры</h3>
            <div class="mb-3">
                <label for="filters" class="form-label">Фильтры</label>
                @foreach($filters as $filter)
                    @if($filter->is_custom_input === null and $filter->required_to_fill_out === null)
                        <h4>{{ $filter->name }}</h4>
                        @if($filter->subfilters)
                            @foreach($filter->subfilters as $subfilter)
                                <div class="mb-3">
                                    <label for="filter_{{ $subfilter->id }}"
                                           class="form-label">{{ $subfilter->name }}</label>
                                    @if($subfilter->required_to_fill_out)
                                        <span class="text-danger">*</span>
                                    @endif
                                    @if($subfilter->is_custom_input)
                                        <input type="text" class="form-control" id="filter_{{ $subfilter->id }}"
                                               name="filters[{{ $subfilter->id }}]">
                                    @else
                                        <select class="form-control" id="filter_{{ $subfilter->id }}"
                                                name="filters[{{ $subfilter->id }}]">
                                            <option value="">Выберите значение</option>
                                            @foreach($filtersValue->where('id_filter', $subfilter->id) as $value)
                                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                </div>
                            @endforeach
                        @endif
                    @else
                        <div class="mb-3">
                            <label for="filter_{{ $filter->id }}"
                                   class="form-label">{{ $filter->name }}</label>
                            @if($filter->required_to_fill_out)
                                <span class="text-danger">*</span>
                            @endif
                            @if($filter->is_custom_input)
                                <input type="text" class="form-control" id="filter_{{ $filter->id }}"
                                       name="filters[{{ $filter->id }}]">
                            @else
                                <select class="form-control" id="filter_{{ $filter->id }}"
                                        name="filters[{{ $filter->id }}]">
                                    <option value="">Выберите значение</option>
                                    @foreach($filtersValue->where('id_filter', $filter->id) as $value)
                                        <option value="{{ $value->id }}">{{ $value->value }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
