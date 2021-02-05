@extends('layouts.app')

@section('title') Pabble: {{ __('lang.change-email') }} @endsection

@section('stylesheets')
    <style>
        @media screen and (max-width: 420px) {
            .button-group a {
                margin-top: 10px;
                display: block;
                margin-right: 15px;
            }
            .button-group button{
                display: block;
                width: calc( 100% - 30px);
            }
        }
    </style>
@endsection

@section('content')
<div class="container mt-7">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('lang.change-email') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('resetEmail') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('current_email') ? ' has-error' : '' }}">
                            <label for="current_email" class="col-md-4 control-label">{{ __('lang.current-email') }}</label>

                            <div class="col-md-6">
                                <input id="current_email" type="email" class="form-control" name="current_email" value="{{ old('current_email') }}" required autofocus>

                                @if ($errors->has('current_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new_email') ? ' has-error' : '' }}">
                            <label for="new_email" class="col-md-4 control-label">{{ __('lang.new-email') }}</label>

                            <div class="col-md-6">
                                <input id="new_email" type="email" class="form-control" name="new_email" value="{{ old('new_email') }}" required autofocus>

                                @if ($errors->has('new_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('confirm_email') ? ' has-error' : '' }}">
                            <label for="confirm_email" class="col-md-4 control-label">{{ __('lang.confirm-new-email') }}</label>

                            <div class="col-md-6">
                                <input id="confirm_email" type="email" class="form-control" name="confirm_email" value="{{ old('confirm_email') }}" required autofocus>

                                @if ($errors->has('confirm_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 button-group">
                                <button type="submit" class="btn btn-primary ml-4">
                                    {{ __('lang.change-email') }}
                                </button>
                                <a class="btn btn-default ml-4 return-profile" href="/u/{{Auth::user()->username}}">
                                    {{ __('lang.return-to-profile') }}
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
