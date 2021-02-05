@extends('layouts.app')

@section('title') Pabble: {{ __('lang.change-password') }} @endsection

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
                <div class="panel-heading">{{ __('lang.change-password') }}</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('resetPassword') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                            <label for="current_password" class="col-md-4 control-label">{{ __('lang.current-password') }}</label>

                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control" name="current_password" value="{{ old('current_password') }}" required autofocus>

                                @if ($errors->has('current_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                            <label for="new_password" class="col-md-4 control-label">{{ __('lang.new-password') }}</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control" name="new_password" value="{{ old('new_password') }}" required autofocus>

                                @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                            <label for="confirm_password" class="col-md-4 control-label">{{ __('lang.confirm-new-password') }}</label>

                            <div class="col-md-6">
                                <input id="confirm_password" type="password" class="form-control" name="confirm_password" value="{{ old('confirm_password') }}" required autofocus>

                                @if ($errors->has('confirm_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-4 button-group">
                                <button type="submit" class="btn btn-primary ml-4">
                                    {{ __('lang.change-password') }}
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
