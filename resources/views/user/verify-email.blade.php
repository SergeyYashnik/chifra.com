@extends('layouts.main')

@section('title', "Login form")

@section('content')
    <div class="alert alert-info" role="alert">
        Спасибо за регистрацию! Подтвердите ваш Email, пройдя по ссылке на почте
    </div>
    <div>
        Не получили письмо?
        <form action="{{ route('verification.send') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-link ps-0">Отправить ещё раз</button>
        </form>
    </div>
@endsection
