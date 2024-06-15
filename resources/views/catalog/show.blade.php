@extends('layouts.main')

@section('title', "Каталог")

@section('content')
    @foreach($catalogs as $catalog)
        <h5 class="card-title">{{ $catalog->name }}</h5>
    @endforeach
@endsection
