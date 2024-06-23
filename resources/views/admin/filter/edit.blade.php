@extends('layouts.main')

@section('title', "Редактирование фильтра")

@section('content')
    @if($filter->id_filter == null)
        <a href="{{ route('admin.catalog.edit', ['id' => $filter->id_catalog]) }}"
           class="btn btn-primary mt-3">Назад</a>
    @elseif($filter->id_catalog == null)
        <a href="{{ route('admin.filter.edit', ['id' => $filter->id_filter]) }}" class="btn btn-primary mt-3">Назад</a>
    @endif

    <div class="container mt-5">
        <h2>Редактировать фильтр</h2>
        <form action="{{ route('admin.filter.update', ['id' => $filter->id]) }}" method="POST" class="mb-5">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $filter->name }}" required>
            </div>
            @if($subfilters->isEmpty())
                @if($filter->is_custom_input !== null)
                    <div class="form-group">
                        <label for="is_custom_input">Произвольный ввод</label>
                        <input type="checkbox" name="is_custom_input" id="is_custom_input"
                               class="form-check-input" {{ $filter->is_custom_input ? 'checked' : '' }}>
                    </div>
                @endif
                @if($filter->required_to_fill_out !== null)
                    <div class="form-group">
                        <label for="required_to_fill_out">Обязателен для заполнения</label>
                        <input type="checkbox" name="required_to_fill_out" id="required_to_fill_out"
                               class="form-check-input" {{ $filter->required_to_fill_out ? 'checked' : '' }}>
                    </div>
                @endif

            @endif
            <button type="submit" class="btn btn-primary mt-3">Сохранить изменения</button>
        </form>

        @if($values->isEmpty() and $filter->lvl < 2)
            <h3>Добавить под фильтр</h3>
            <form action="{{ route('admin.filter.addSubfilter', ['id' => $filter->id]) }}" method="POST" class="mb-5">
                @csrf
                <div class="form-group">
                    <label for="subfilter-name">Название под фильтра</label>
                    <input type="text" name="name" id="subfilter-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="is_custom_input_subfilter">Произвольный ввод</label>
                    <input type="checkbox" name="is_custom_input" id="is_custom_input_subfilter"
                           class="form-check-input">
                </div>
                <div class="form-group">
                    <label for="required_to_fill_out_subfilter">Обязателен для заполнения</label>
                    <input type="checkbox" name="required_to_fill_out" id="required_to_fill_out_subfilter"
                           class="form-check-input">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Добавить под фильтр</button>
            </form>
        @endif
        @if($subfilters->isEmpty())
            <h3>Добавить значение</h3>
            <form action="{{ route('admin.filter.addValue', ['id' => $filter->id]) }}" method="POST" class="mb-5">
                @csrf
                <div class="form-group">
                    <label for="value">Значение</label>
                    <input type="text" name="value" id="value" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Добавить значение</button>
            </form>
        @endif

        @if(!$subfilters->isEmpty())
            <h3>Подфильтры</h3>
            @foreach($subfilters as $subfilter)
                <div class="row align-items-center mb-3 border p-3">
                    <div class="col-md-6">
                        <h5>{{ $subfilter->name }}</h5>
                        <p>Произвольный ввод: {{ $subfilter->is_custom_input ? 'Да' : 'Нет' }}</p>
                        <p>Обязателен для заполнения: {{ $subfilter->required_to_fill_out ? 'Да' : 'Нет' }}</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('admin.filter.edit', ['id' => $subfilter->id]) }}"
                           class="btn btn-warning btn-sm">Настройки</a>
                        <form action="{{ route('admin.filter.destroy', ['id' => $subfilter->id]) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!$values->isEmpty())
            <h3>Значения</h3>
            @foreach($values as $value)
                <div class="row align-items-center mb-3 border p-3">
                    <div class="col-md-8">
                        <h5>{{ $value->value }}</h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <form
                            action="{{ route('admin.filter.removeValue', ['id' => $filter->id, 'valueId' => $value->id]) }}"
                            method="POST" style="display:inline;">
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
