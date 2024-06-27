@extends('layouts.main')

@section('title', $user->name)

@section('content')
    <h1>Профиль пользователя</h1>

    <p><strong>Ваш уникальный номер:</strong> {{ $user->id }}</p>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.updateName') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Обновить имя</button>
    </form>
    <form action="{{ route('profile.updatePhone') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="phone">Номер телефона</label>
            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Изменить номер телефона</button>
    </form>

    <form action="{{ route('profile.updatePassword') }}" method="POST" class="mt-3">
        @csrf
        <div class="form-group">
            <label for="current_password">Текущий пароль</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Новый пароль</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Подтвердите новый пароль</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                   required>
        </div>
        <button type="submit" class="btn btn-primary">Обновить пароль</button>
    </form>

    <form action="{{ route('profile.updateLogin') }}" method="POST" class="mt-3">
        @csrf
        <div class="form-group">
            <label for="email">Логин (Email)</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Обновить логин</button>
    </form>

    <h2>Адреса доставки</h2>
    @if ($user->addresses->isEmpty())
        <p>У вас пока нет сохраненных адресов доставки.</p>
    @else
        <ul>
            @foreach ($user->addresses as $address)
                <li>
                    <strong>Город:</strong> {{ $address->city->name }},
                    <strong>Улица:</strong> {{ $address->street }},
                    <strong>Дом:</strong> {{ $address->house }},
                    @if ($address->apartment)
                        <strong>Квартира:</strong> {{ $address->apartment }}
                    @endif
                    <form action="{{ route('address.delete', $address->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
    <h2>Добавить новый адрес доставки</h2>
    <form action="{{ route('profile.addAddress') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="city_id">Город</label>
            <select name="city_id" id="city_id" class="form-control" required>
                <option value="">Выберите город</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="street">Улица</label>
            <input type="text" name="street" id="street" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="house">Дом</label>
            <input type="text" name="house" id="house" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apartment">Квартира (при наличии)</label>
            <input type="text" name="apartment" id="apartment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Добавить адрес</button>
    </form>

@endsection
