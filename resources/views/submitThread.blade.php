@extends('layouts.app')

@section('title') Pabble: {{ __('lang.create-new-post') }} @endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('css/easy-autocomplete.min.css')}}">

    <style>
        .dropzone {
            border: 2px dashed #0087F7;
            border-radius: 5px;
            background: white;
            min-height: 150px;
            padding: 54px 54px;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-3">
        <div class="row panel panel-default ml-3 mr-3 pb-3">
            <div class="col-md-12">
                @if(isset($name))
                    <h2>{{ __('lang.posting-in') }} <a href="/p/{{$name}}">/p/{{$name}}</a></h2>
                @else
                    <h2>{{ __("lang.post-something-new") }}</h2>
                @endif

                <ul class="nav nav-tabs">
                    <li @if(app('request')->input('type') == 'link') class="active"  @elseif(empty(app('request')->input('type'))) class="active" @endif><a data-toggle="tab" href="#link">{{ __('lang.link') }}</a></li>
                    <li @if(app('request')->input('type') == 'text') class="active" @endif><a data-toggle="tab" href="#text">{{ __('lang.text') }}</a></li>
                </ul>

                <div class="tab-content">
                    <div id="link" class="tab-pane fade @if(app('request')->input('type') == 'link') in active  @elseif(empty(app('request')->input('type'))) in active @endif">
                        <form id="link_form" action="" method="post" class="form-horizontal">
                            {{ csrf_field() }}

                            <input type="hidden" name="type" value="link">

                            <div class="mt-3 form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                <h4>{{ __("lang.url") }}</h4>
                                    <input type="text" id="url" class="form-control" name="url" placeholder="{{ __("lang.url") }}" value="@if (!$errors->has('url')){{old('url')}}@endif" autocomplete="off">
                                    @if ($errors->has('url'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                <h4>{{ __('lang.title') }} <span class="text-red">*</span></h4>
                                    <textarea id="title" class="form-control w-full" name="title" placeholder="{{ __('lang.title') }}" cols="30" rows="2">@if (!$errors->has('title')){{old('title')}}@endif</textarea>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('subpabble') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                <h4>{{ __('lang.subpabble') }} <span class="text-red">*</span></h4>
                                    <input autocomplete="off" type="text" id="subpabble" class="form-control" name="subpabble" placeholder="{{ __('lang.subpabble') }}" value="@if (!empty(old('subpabble'))){{old('subpabble')}}@elseif(isset($name)){{$name}}@endif">
                                    @if ($errors->has('subpabble'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('subpabble') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </form>

                        <div class="row">
                            <div class="col-md-6">
                                <div id="dropzone">
                                    <form id="drop_zone" action="/api/upload/media" class="dropzone" enctype="multipart/form-data">
                                        <input type="hidden" name="api_token" value="{{Auth::user()->api_token}}">
                                        <div class="dz-message" data-dz-message><span>{{ __('lang.drop-your-media-here') }} <br>jpg, png, gif, webm, mp4</span></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 mb-3" class="form-group">
                            <div class="col-md-6">
                                <button id="submit_link" class="btn btn-primary pull-right">&nbsp;&nbsp;&nbsp;&nbsp;{{ __('lang.post-it') }}&nbsp;&nbsp;&nbsp;&nbsp;</button>
                            </div>
                        </div>

                    </div>


                    <div id="text" class="tab-pane fade @if(app('request')->input('type') == 'text') in active @endif">
                        <form action="" method="post" class="form-horizontal">
                            {{ csrf_field() }}

                            <input type="hidden" name="type" value="text">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }} mt-3">
                                <div class="col-md-6">
                                <h4>{{ __('lang.title') }} <span class="text-red">*</span></h4>
                                    <textarea id="title" class="form-control w-full" name="title" placeholder="{{ __('lang.title') }}" cols="30" rows="2">@if (!$errors->has('title')){{old('title')}}@endif</textarea>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('text') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                <h4>{{ __('lang.text') }}</h4>
                                    <textarea id="text" class="form-control w-full" name="text" placeholder="{{ __('lang.text') }}" cols="30" rows="10">@if (!$errors->has('text')){{old('text')}}@endif</textarea>

                                    @if ($errors->has('text'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('text') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('subpabble') ? ' has-error' : '' }}">
                                <div class="col-md-6">
                                <h4>{{ __('lang.subpabble') }} <span class="text-red">*</span></h4>
                                    <input autocomplete="off" id="subpabble2" class="form-control w-full" name="subpabble" placeholder="{{ __('lang.subpabble') }}" value="@if (!empty(old('subpabble'))){{old('subpabble')}}@elseif(isset($name)){{$name}}@endif">

                                    @if ($errors->has('subpabble'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('subpabble') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;{{ __('lang.post-it') }}&nbsp;&nbsp;&nbsp;&nbsp;" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/dropzone.js')}}"></script>
    <script src="{{asset('js/jquery.easy-autocomplete.min.js')}}"></script>
    <script>
        var baseurl = '{{url('/')}}';
        var key = '';

        $('#submit_link').click(function() {
            $('#link_form').submit();
        });

        Dropzone.autoDiscover = false;
        $(".dropzone").dropzone({
            maxFiles: 1,
            maxFilesize: 4, // MB
            addRemoveLinks: true,
            removedfile: function(file) {
                $('#url').val('');
                file.previewElement.remove();
                $.get( "api/media/delete/"+key);
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.mp4",
            success : function(file, response){
                key = response.key;
                $('#url').val(baseurl + '/media/' + response.link);
            }
        });

        var options = {

            url: function(phrase) {
                return "api/subpabbles/search/"+phrase;
            },

            getValue: function(element) {
                return element.name;
            },

            ajaxSettings: {
                dataType: "json",
                method: "GET",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $("#example-ajax-post").val();
                return data;
            },

            requestDelay: 400
        };

        $("#subpabble").easyAutocomplete(options);
        $("#subpabble2").easyAutocomplete(options);
        $('div.easy-autocomplete').removeAttr('style');
    </script>
@endsection
