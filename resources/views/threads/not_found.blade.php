@extends('layouts.app')

@section('title') Whoops... @endsection

@php $twitter_title = __('lang.sorry-that-thread-is-gone'); @endphp
@include('layouts.partials.twitter_cards')

@section('content')

    <div class="container">
        <p>{{ __('lang.the-thread-you-where-looking-for-was-not-found') }}</p>
    </div>

@endsection
