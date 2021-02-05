@extends('layouts.app')

@section('title')
    Pabble: {{ __('lang.send-private-message') }}
@endsection


@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    <link rel="stylesheet" href="{{asset('css/easy-autocomplete.min.css')}}">
    <style>
        #header {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <h2 id="header">{{ __('lang.send-private-message') }}</h2>

        <form id="link_form" action="{{ route('messages.send') }}" method="post" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                <div class="container ml-0">
                    <h4>{{ __('lang.subject') }} <span class="red">*</span></h4>
                </div>
                <div class="col-md-6">
                    <input type="text" id="subject" class="form-control" name="subject" placeholder="Subject" cols="30" rows="2" value="@if (!$errors->has('subject')){{old('subject')}}@endif" />

                    @if ($errors->has('subject'))
                        <span class="help-block">
                            <strong>{{ $errors->first('subject') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                <div class="container ml-0">
                    <h4>{{ __('lang.message') }} <span class="red">*</span></h4>
                </div>
                <div class="col-md-6">
                    <textarea id="message" class="form-control" name="message" placeholder="Message" cols="30" rows="2">@if (!$errors->has('message')){{old('message')}}@endif</textarea>

                    @if ($errors->has('message'))
                        <span class="help-block">
                            <strong>{{ $errors->first('message') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
                <div class="container ml-0">
                    <h4>{{ __('lang.to') }} <span class="red">*</span></h4>
                </div>
                <div class="col-md-6">
                    <input autocomplete="off" type="text" id="subpabble" class="form-control" name="to" placeholder="To" @if (!$errors->has('to') && $username) value="{{$username}}" @endif @if (!$errors->has('to')) value="{{old('to')}}"@endif>
                    @if ($errors->has('to'))
                        <span class="help-block">
                            <strong>{{ $errors->first('to') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group mt-7">
                <div class="col-md-6">
                    <button id="submit_link" class="btn btn-primary pull-right">{{ __('lang.send-message') }}</button>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/jquery.easy-autocomplete.min.js')}}"></script>
    <script>
        var options = {

            url: function(phrase) {
                return "/api/users/search/"+phrase;
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
