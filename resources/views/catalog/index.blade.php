@extends('layouts.main')

@section('title', "Каталог")

@section('content')
    <h1 class="my-4">Каталог</h1>
    <div class="row">
        @foreach($catalogs as $catalog)
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center">
                    @if($catalog->image)
                        <img src="{{ asset('storage/' . $catalog->image) }}" class="card-img-top mx-auto d-block" alt="{{ $catalog->name }}" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $catalog->name }}</h5>
                        <a href="{{ route('catalog.show', ['catalog_lvl_1' => $catalog->name]) }}" class="btn btn-primary">Посмотреть</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
