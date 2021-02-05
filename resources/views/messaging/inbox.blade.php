@extends('layouts.app')

@section('title')
    Pabble: {{ __('lang.messages-inbox') }}
@endsection


@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    <style>
        .tabmenu li {
            border: 1px solid #5f99cf;
        }
        .pm_padding {
            margin-left: 10px;
        }
        .subject {
            margin-bottom: 0;
            margin-top: 2px;
        }
        .time {
            margin-right: -40px;
        }
        .thing_wrap {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            padding-top: 10px;
            padding-left: 30px;
        }
        .read {
            background-color: #f9f9f9;
        }
        .pm {
            margin-bottom: 5px;
        }
        #submit {
            background:none!important;
            border:none;
            color: #3097D1;
            padding:0!important;
            font: inherit;
            /*border is optional*/
            cursor: pointer;
        }
        #submit:hover {
            text-decoration: underline;
        }
        .rightmenu{
            white-space: nowrap;
            vertical-align: bottom;
            display: flex;
        }
        .rightmenu li{
            border-radius: 5px;
            border: 1px solid #bebebe;
            background-color: #e7e7e7;
            padding: 3px 3px;
            display: inline;
            margin-right: 5px;
        }
        .envelope{
            width: 20px;
            font-size: 25px;
            padding-left: 10px;
        }
        .time_wrap .time{
            float: right;
            margin-top: 10px;
        }
        @media screen and (max-width: 560px) {
            .rightmenu {
                margin-top: 0;
                position: relative;
                float: right;
                margin-right: -20px;
            }
            .time {
                margin-right: -50px;
            }
        }
        @media screen and (max-width: 992px) {
            .time {
                margin-right: -20px;
            }
        }
        @media screen and (max-width: 610px) {
            .time {
                margin-right: -10px;
            }
        }
        @media screen and (max-width: 510px) {
            .time {
                display: none;
            }
            .time_wrap {
                display: none;
            }
            .thing_wrap {
                width: 91%;
            }
        }
        @media screen and (max-width: 335px) {
            .thing_wrap {
                width: 85%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-7">
        <!-- <ul style="float: left; padding: 0; margin-top: 10px; position: absolute">
            <h4>Unread</h4>
        </ul> -->
        <form action="{{ route('messages.mark_read') }}" method="post">
            {{ csrf_field()  }}
            <ul class="rightmenu mt-3">
                <li class="selected tabmenu_bottom"><button id="submit" type="submit">{{ __('lang.mark-all-as-read') }}</button></li>
                <li class="selected tabmenu_bottom"><a href="{{ route('messages.send') }}">{{ __('lang.send-pm') }}</a></li>
            </ul>
        </form>
    </div>

    <div class="container">

        @if(count($messages) < 1)
            <p>{{ __('lang.your-inbox-appears-to-be-empty') }}</p>
        @endif

        @foreach($messages as $pm)
            <a href="{{ route('message.view', $pm->code) }}">
                <div class="panel @if($pm->active == 0) read @endif pm">
                    <div class="row">
                        <div class="envelope col-xs-1">
                            <i class="fa @if($pm->active) fa-envelope-o @else fa-envelope-open-o @endif envelope" aria-hidden="true"></i>
                        </div>
                        <div class="col-xs-9 thing_wrap flex">
                            <h4 class="pm_padding subject overflow">{{$pm->subject}}</h4>
                            <span href="/u/{{$pm->from}}" class="pm_padding">{{$pm->from}}</span>
                        </div>
                        <div class="col-xs-2 time_wrap">
                            <p class="time">{{Carbon\Carbon::parse($pm->created_at)->diffForHumans()}}</p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

        @if($messages->currentPage() > 1)
            <a href="{{$messages->previousPageUrl()}}">{{ __('lang.prev') }}</a>
        @endif
        @if($messages->currentPage() > 1 && $messages->currentPage() !== $messages->lastPage())
            -
        @endif
        @if($messages->currentPage() > 0 && $messages->currentPage() !== $messages->lastPage())
            <a href="{{$messages->nextPageUrl()}}">{{ __('lang.next') }}</a>
        @endif

    </div>

@endsection
