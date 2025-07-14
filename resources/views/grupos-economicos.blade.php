@extends('layouts.app')

@section('title', 'Grupos Econômicos')

@section('header')
    <h2 class="text-xl font-semibold text-gray-800 leading-tight">
        Grupos Econômicos
    </h2>
@endsection

@section('content')
    <livewire:grupo-economico-component />
@endsection
