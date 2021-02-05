@extends('layouts.app')

@section('title') Pabble: {{ __('lang.resend-activation-code') }} @endsection

@php $twitter_title = 'Login'; @endphp
@include('layouts.partials.twitter_cards')

@section('content')
    <div class="container mt-7">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('lang.resend-activation-code') }}</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('auth.activate.resend') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">{{ __('lang.email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="email" value="{{ old('username') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('lang.resend') }}
                                    </button>

                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('lang.login') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
