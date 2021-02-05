@extends('layouts.app')

@section('title') Pabble: {{ __('lang.create-subpabble') }} @endsection

@section('content')
    <div class="container mt-7">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel">
                <div class="panel-heading">
                    <h2 class="overflow">{{ __("lang.you-are-creating") }} /p/<span id="is_creating">{{ old('name') }}</span></h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="" method="post">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">{{ __('lang.name') }}</label>
                            <div class="col-md-9">
                                <input autocomplete="off" placeholder="{{ __('lang.name') }}" id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-2 control-label">{{ __('lang.title') }}</label>
                            <div class="col-md-9">
                                <input placeholder="{{ __('lang.title') }}" id="title" type="text" class="form-control" name="title" value="{{ old('title') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-2 control-label">{{ __('lang.description') }}</label>
                            <div class="col-md-9">
                                <textarea placeholder="{{ __('lang.description') }}" id="description" class="form-control w-full" name="description">{{ old('description') }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('social_description') ? ' has-error' : '' }}">
                            <label for="SocialDescription" class="col-md-2 control-label">{{ __('lang.social-description') }}</label>
                            <div class="col-md-9">
                                <textarea placeholder="{{ __('lang.social-description') }}" id="SocialDescription" class="form-control w-full" name="social_description">{{ old('social_description') }}</textarea>

                                @if ($errors->has('social_description'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('social_description') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-11">
                                <input type="submit" value="{{ __('lang.create-subpabble') }}" class="btn btn-primary pull-right">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#name').on("input", function() {
            $('#is_creating').text($('#name').val());
        });
    </script>
@endsection
