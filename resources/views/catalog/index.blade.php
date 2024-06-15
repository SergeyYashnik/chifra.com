@extends('layouts.main')

@section('title', "Каталог")

@section('content')
    <h1 class="my-4">Каталог</h1>
    <div class="row">
    @foreach($catalogs as $catalog)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($catalog->image)
                        <img src="{{ asset('storage/' . $catalog->image) }}" class="card-img-top" alt="{{ $catalog->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $catalog->name }}</h5>
                        <a href="{{ route('catalog.show', ['id' => $catalog->id]) }}" class="btn btn-primary">Посмотреть</a>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
@endsection
