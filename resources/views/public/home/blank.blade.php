@extends('public.layouts.main')
@section('title')
    {{ $product->meta_title ?? $product->name }}
@endsection
@section('meta')
    <meta name="description" content="{{ $product->meta_description }}">
    <meta name="keywords" content="{{ $product->meta_keywords }}">
@endsection
@section('content')

@endsection
