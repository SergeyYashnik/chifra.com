@extends('layouts.main')

@section('title', "Редактирование товаров")

@section('content')
    @include('include.admin_menu')
    <div class="container mt-4">
        <h1>Редактирование товара</h1>

        <!-- Изображения -->
        <div class="mb-3">
            <div class="mt-2">
                <h5>Текущие изображения:</h5>
                @foreach($images as $image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Изображение товара" width="100">
                        <form action="{{ route('admin.product.deleteImage', ['id' => $image->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger mt-1">Удалить</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Название товара -->
            <div class="mb-3">
                <label for="name" class="form-label">Название товара</label>
                <span class="text-danger">*</span>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>

            <!-- Описание товара -->
            <div class="mb-3">
                <label for="description" class="form-label">Описание товара</label>
                <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
            </div>

            <!-- Цена товара -->
            <div class="mb-3">
                <label for="price" class="form-label">Цена товара</label>
                <span class="text-danger">*</span>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </div>

            <!-- Бренд -->
            <div class="mb-3">
                <label for="brand" class="form-label">Бренд</label>
                <span class="text-danger">*</span>
                <select class="form-control" id="brand_id" name="brand_id" required>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <input type="number" class="form-control" id="id_catalog" name="id_catalog" value="{{ $product->id_catalog }}" hidden>

            <!-- Количество товара на складе -->
            <div class="mb-3">
                <label for="quantity" class="form-label">Количество товара на складе</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}" required>
            </div>

            <!-- Скидка -->
            <div class="mb-3">
                <label for="sale" class="form-label">Скидка (%)</label>
                <input type="number" class="form-control" id="sale" name="sale" value="{{ $product->sale }}" min="0" max="100">
            </div>

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
                                               name="filters[{{ $subfilter->id }}]"
                                               @if(isset($selectedFiltersValue[$subfilter->id]))
                                                   value="{{ $selectedFiltersValue[$subfilter->id] }}"
                                            @endif>
                                    @else
                                        <select class="form-control" id="filter_{{ $subfilter->id }}"
                                                name="filters[{{ $subfilter->id }}]">
                                            <option value="">Выберите значение</option>
                                            @foreach($filtersValue->where('id_filter', $subfilter->id) as $value)
                                                <option value="{{ $value->id }}" @if(isset($selectedFiltersValue[$value->id]))
                                                    selected
                                                    @endif>{{ $value->value }}</option>
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
                                       name="filters[{{ $filter->id }}]" @if(isset($selectedFiltersValue[$filter->id]))
                                           value="{{ $selectedFiltersValue[$filter->id] }}"
                                       @endif>
                            @else
                                <select class="form-control" id="filter_{{ $filter->id }}"
                                        name="filters[{{ $filter->id }}]">
                                    <option value="">Выберите значение</option>
                                    @foreach($filtersValue->where('id_filter', $filter->id) as $value)
                                        <option value="{{ $value->id }}" @if(isset($selectedFiltersValue[$value->id]))
                                            selected
                                            @endif>{{ $value->value }}</option>
                                        @dump($value)
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
