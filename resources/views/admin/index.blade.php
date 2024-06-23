@extends('layouts.main')

@section('title', "Настройки сайта")

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <a href="{{ route('admin.catalog') }}" class="btn btn-primary btn-lg btn-block mb-3">
                    Управление каталогом
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.users') }}" class="btn btn-primary btn-lg btn-block mb-3">
                    Управление пользователями
                </a>
            </div>
        </div>
    </div>
@endsection
