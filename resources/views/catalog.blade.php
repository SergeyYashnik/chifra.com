@extends('layouts.main')

@section('title', "Каталог")

@section('content')
    @foreach($catalogs as $catalog)
    {{ $catalog->name }}
    @endforeach
@endsection
