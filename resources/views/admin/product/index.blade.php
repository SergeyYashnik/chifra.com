@extends('layouts.main')

@section('title', "Редактирование товаров")

@section('content')

@foreach($filters as $filter)
    <b>
        {{ $filter->name }}
    </b>
    <br>
    @foreach($filter->subfilters as $subfilter)
        {{ $subfilter->name }}
        <br>
    @endforeach

@endforeach



@endsection
