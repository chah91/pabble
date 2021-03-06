@extends('layouts.app')

@section('title') {{__('lang.edit').' '.$pabble->name }} @endsection

<link rel="stylesheet" href="{{asset('css/easy-autocomplete.min.css')}}">
<link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}">
@section('stylesheets')
    <style>
        .container {
            font-family: roboto;
            font-weight: 300;
        }
        .bootstrap-tagsinput {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')

    <div class="container mt-7">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel">
                <div class="panel-heading">
                    <h2>{{ __('lang.edit') }} <a href="/p/{{$pabble->name}}">/p/{{$pabble->name}}</a></h2>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-2 control-label">{{ __('lang.title') }}</label>
                            <div class="col-md-9">
                                <input placeholder="{{ __('lang.title') }}" id="title" type="text" class="form-control" name="title" value="@if (!empty(old('title'))) {{ old('title') }} @else{{$pabble->title}}@endif">

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
                                <textarea name="description" id="description" placeholder="{{ __('lang.description') }}" cols="30" rows="5" class="form-control w-full">@if (!empty(old('description'))) {{ old('description') }} @else{{$pabble->description}}@endif</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('social_description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-2 control-label">{{ __('lang.social-description') }}</label>
                            <div class="col-md-9">
                                <textarea name="social_description" id="social_description" placeholder="{{ __('lang.social-description') }}" cols="30" rows="5" class="form-control w-full">@if (!empty(old('social_description'))) {{ old('social_description') }} @else{{$pabble->description_social}}@endif</textarea>
                                @if ($errors->has('social_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('social_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('header') ? ' has-error' : '' }}">
                            <label for="header" class="col-md-2 control-label">{{ __('lang.header-picture') }}</label>
                            <div class="col-md-9">
                                <input placeholder="{{ __('lang.header-picture') }}" id="header" type="file" class="form-control" name="header" value="{{ old('header') }}">

                                @if ($errors->has('header'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('header') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($pabble->header)
                            <div class="form-group">
                                <label for="header" class="col-md-2 control-label">{{ __('lang.current-header') }}</label>
                                <div class="col-md-10">
                                    <img class="w-full" src="/images/pabbles/headers/{{$pabble->header}}" alt="{{$pabble->title}}">
                                    <label class="checkbox-inline">
                                        <input @if($pabble->header_type == 'fit') checked @endif type="checkbox" name="header_type"> {{ __('lang.stretch-header-to-full-width') }}
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="delete_header"> {{ __('lang.delete-header-image') }}
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                            <label for="icon" class="col-md-2 control-label">{{ __('lang.icon') }}</label>
                            <div class="col-md-9">
                                <input placeholder="{{ __('lang.icon') }}" id="icon" type="file" class="form-control" name="icon" value="{{ old('icon') }}">

                                @if ($errors->has('icon'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('icon') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($pabble->icon)
                            <div class="form-group">
                                <label for="icon" class="col-md-2 control-label">{{ __('lang.current-icon') }}</label>
                                <div class="col-md-10">
                                    <img class="max-h-100" src="/images/pabbles/icons/{{$pabble->icon}}" alt="{{$pabble->title}}">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="delete_icon"> {{ __('lang.delete-icon') }}
                                    </label>
                                </div>
                            </div>
                        @endif


                        <div class="form-group {{ $errors->has('moderator') ? ' has-error' : '' }}">
                            <label for="moderator" class="col-md-2 control-label">{{ __('lang.moderators') }}</label>
                            <div class="col-md-9">
                                <input data-role="tagsinput" id="moderator" type="text" class="form-control" name="moderator" value="{{$mods}}">

                                @if ($errors->has('moderator'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('moderator') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('header_color') ? ' has-error' : '' }}">
                            <label for="header_color" class="col-md-2 control-label">{{ __('lang.header-color') }}</label>
                            <div class="col-md-9">
                                <input id="header_color" type="text" class="form-control jscolor" name="header_color" value="@if (!empty(old('header_color'))) {{ old('header_color') }} @else{{$pabble->header_color}}@endif">

                                @if ($errors->has('header_color'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('header_color') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('color') ? ' has-error' : '' }}">
                            <label for="color" class="col-md-2 control-label">{{ __('lang.color') }}</label>
                            <div class="col-md-9">
                                <input id="header_color" type="text" class="form-control jscolor" name="color" value="@if (!empty(old('color'))) {{ old('color') }} @else{{$pabble->color}}@endif">

                                @if ($errors->has('color'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('color') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-11">
                                <div class="col-md-2 col-md-offset-2 col-xs-2">
                                    <a href="/p/{{$pabble->name}}/edit/css">{{ __('lang.edit-css') }}</a>
                                </div>
                                <div class="col-md-8 col-xs-10">
                                    <input type="submit" value="{{ __('lang.update-pabble') }}" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/jscolor.min.js') }}"></script>
    <script src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>
@endsection
