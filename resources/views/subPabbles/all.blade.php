@extends('layouts.app')

@section('title') {{ __("lang.all-subpabbles") }} @endsection

@section('stylesheets')
    <style>
        .searchbar{
            background: #ddd;
            display: flex;
            padding: 13px 20px;
        }
        .searchbar .fa-search.left{
            font-size: 26pt;
            margin: auto 0px;
        }
        .no-border{
            border: none;
        }
        .searchbar .description{
            font-size: 10pt;
        }
        .info-bar{
            background: #f6e69f;
            padding: 10px;
            border: 2px solid #fcb527;
        }
        .info-bar span{
            background: #fff;
        }
        .notsubscribed {
            background-color: #4CAF50 !important;
            color:white;
            border: 1px solid #4CAF50 !important;
            border-top: 1px solid #4CAF50 !important;
            margin: auto 0px;
        }
        .subscribed {
            background-color: #F44336 !important;
            color:white;
            border: 1px solid #F44336 !important;
            border-top: 1px solid #F44336 !important;
            margin: auto 0px;
        }
        button:hover, button:focus{
            color: #fff !important;
        }
        .title{
            font-size: 15pt;
        }
        .subpabble-item{
            display: flex;
        }
        .subpabble-item .description{
            border-radius: 5px;
            border: 1px solid #999;
            padding: 3px;
            background: #efefef;
        }
        .subscribe {
            transition: 200ms;
        }
        .subscribe:hover{
            cursor: pointer;
            padding-left: 20px;
            padding-right: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row panel panel-default pb-3 no-border">
            <form action="/subpabbles" method="GET" class="searchbar">
                <span class="fa fa-search left"></span>
                <div class="col-sm-6 col-md-4 ml-6">
                    <span class="description">{{ __('lang.search-subpabble-by-name') }}</span>
                    <div class="input-group ">
                        <input value="{{ Request::input('search') }}" type="text" name="search" class="search-query form-control" placeholder="{{ __('lang.search') }}" />
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">
                                <span class="fa fa-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
            <div class="info-bar">
                {{ __('lang.click-the') }} <span>{{ __('lang.subscribe') }}</span> {{ __('lang.or') }} <span>{{ __('lang.unsubscribe') }}</span> {{ __('lang.buttons-to-choose-which-subpabble-appear-on-the-home-feed') }}
            </div>
            @foreach($subpabbles as $item)
            <div class="col-md-12 mt-3 subpabble-item">
                @php
                    if (Auth::user())
                    $subscribed = \App\Models\Subscription::where('user_id', Auth::user()->id)->where('sub_pabble_id', $item->id)->first();
                    else $subscribed = null;
                @endphp
                <button onclick="subscribeManage('{{ $item->name }}', this)" @if(!$subscribed) data-subscribed="no" @else data-subscribed="yes" @endif class="btn btn-default @if(!$subscribed) notsubscribed @else subscribed @endif selected subscribe text-white text-capitalize">@if(!$subscribed) {{ __('lang.subscribe') }} @else  {{ __('lang.unsubscribe') }} @endif</button>
                <div class="ml-6">
                    <a href="/p/{{ $item->name }}" class="title">/p/{{ $item->name }} : {{ $item->name }}</a>
                    <div class="description">{{ $item->description }}</div>
                    @php $subscribers = \App\Models\Subscription::where('sub_pabble_id', $item->id)->count(); @endphp
                    <p class="mt-0">{{$subscribers}} {{$subscribers < 2 ? __('lang.subscriber') : __('lang.subscribers')}},
                    {{ __('lang.a-community-for') }} {{Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                </div>
            </div>
            <hr class="w-full mt-0 mb-0">
            @endforeach
            <div class="text-center">{{ $subpabbles->links() }}</div>
        </div>
    </div>
    @include('layouts.partials.loginModal')
@endsection
@section('scripts')
<script>
    function subscribeManage(pabble, target){
        console.log('123')
        let subscribed = $(target).attr('data-subscribed');
        @if(Auth::check())
            data = {'api_token': '{{Auth::user()->api_token}}'};
            if (subscribed === 'no') {
                $.post( "/api/subscribe/" + pabble, data, function( res ) {
                    $(target).removeClass('notsubscribed').addClass('subscribed').attr('data-subscribed', 'yes').text('{{ __('lang.unsubscribe') }}');
                });
            } else {
                $.post( "/api/unsubscribe/" + pabble, data, function( res ) {
                    $(target).removeClass('subscribed').addClass('notsubscribed').attr('data-subscribed', 'no').text('{{ __('lang.subscribe') }}');
                });
            }
        @else
            $('#loginModal').modal('show');
            $('#loginModalMessage').html('{{ __('lang.to-subscribe') }}');
        @endif
    }
</script>
@endsection
