@extends('layouts.main')

@section('title', "Обновление статусов")

@section('content')
    @include('include.admin_menu')
    <h1>Управление пользователями</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.user.search') }}" method="GET">
        <div class="form-group">
            <label for="user_id">ID пользователя</label>
            <input type="number" name="user_id" id="user_id" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Найти пользователя</button>
    </form>

    @if (isset($user))
        <h2>Пользователь: {{ $user->name }}</h2>
        <p>Email: {{ $user->email }}</p>
        <form action="{{ route('admin.user.update') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="form-group">
                <label for="role">Роль</label>
                <select name="role" id="role" class="form-control" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Обновить роль</button>
        </form>
    @else
        <p>Пользователей с таким id нет, попробуйте ввести другой</p>
    @endif

@endsection
