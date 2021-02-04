@extends('layouts.app')

@section('title') @if(isset($subPabble->name)) p/{{ $subPabble->name }} @else What happened? @endif @endsection

@php $twitter_title = __('lang.search-in') . $subPabble->name; @endphp
@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    @if($subPabble)
        <style>
            .header {
                @if($subPabble->header)
                background: linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.2)),url("/images/pabbles/headers/{{$subPabble->header}}");
                @endif
                background-position: center;
                @if($subPabble->header_type == 'fit')
                   background-size: cover;
                @endif
                width: 100%;
                @if(!$subPabble->header)
                background: {{$subPabble->header_color}};
                @else
                margin-top: 0;
            @endif
}
            @if($subPabble->header_color)
                #header_name {
                color: {{$subPabble->color}};
            }
            #header_title {
                color: {{$subPabble->color}};
            }
            @endif
            .notsubscribed {
                background-color: #4CAF50 !important;
                color:white;
                border: 1px solid #4CAF50 !important;
                border-top: 1px solid #4CAF50 !important;
            }
            .subscribed {
                background-color: #F44336 !important;
                color:white;
                border: 1px solid #F44336 !important;
                border-top: 1px solid #F44336 !important;
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
    @endif
@endsection

@section('content')

    @if($subPabble)

        <div class="container mt-3">
            <div class="row panel panel-default mr-3 pb-3 pl-3 pr-3">
                <h2 class="thin text-center">Search in <a href="/p/{{$subPabble->name}}">/p/{{$subPabble->name}}</a> : {{ $subPabble->title }}</h2>
                <form method="GET" action="/search/{{$subPabble->name}}">
                    <div id="custom-search-input">
                        <div class="input-group col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                            <input value="{{ Request::input('q') }}" type="text" name="q" class="search-query form-control" placeholder="{{ __('lang.search') }}" />
                            <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="fa fa-search"></span>
                                    </button>
                            </span>
                        </div>
                    </div>
                    <div class="tabmenu">
                        @php
                            $queries = request()->query();
                            if (isset($queries['sort'])){
                                unset($queries['sort']);
                            }
                            $current_url = url()->current().'?'.http_build_query($queries);
                        @endphp
                        <li @if(isset(request()->query()['sort']) && request()->query()['sort'] == 'popular') class="selected" @endif>
                            <a href="{{ $current_url.'&sort=popular' }}">{{ __('lang.popular') }}</a>
                        </li>
                        <li @if(isset(request()->query()['sort']) && request()->query()['sort'] == 'new') class="selected" @endif>
                            <a href="{{ $current_url.'&sort=new' }}">{{ __('lang.new') }}</a>
                        </li>
                        <li @if(isset(request()->query()['sort']) && request()->query()['sort'] == 'top') class="selected" @endif>
                            <a href="{{ $current_url.'&sort=top' }}">{{ __('lang.top') }}</a>
                        </li>
                    </div>
                </form>

                @php
                    $user = new \App\Models\User();
                @endphp
                <div class="row pl-3 pr-3">
                    @if($threads)
                        @foreach($threads as $thread)
                            @php $postername = $user->select('username')->where('id', $thread->poster_id)->first(); @endphp
                            <div class="panel mt-3">
                                <div class="thread">
                                    <div class="votes col-xs-1">
                                        <div class="row stack upvote">
                                            <i id="{{$thread->id}}_up" data-voted="no" data-vote="up" data-thread="{{$thread->code}}" class="fa fa-sort-asc vote"></i>
                                        </div>
                                        <div class="row stack">
                                            <span id="{{$thread->id}}_counter" class="stack count">{{$thread->upvotes - $thread->downvotes}}</span>
                                        </div>
                                        <div class="row stack downvote">
                                            <i id="{{$thread->id}}_down" data-voted="no" data-vote="down" data-thread="{{$thread->code}}" class="fa fa-sort-desc stack vote"></i>
                                        </div>
                                    </div>
                                    <div class="image col-xs-1">
                                        <div class="row">
                                            <a href="@if(!$thread->media_type && $thread->type === 'link') {{$thread->link}} @else {{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                                <img src="@if($thread->thumbnail !== null){{$thread->thumbnail}} @elseif($thread->link) {{url('/')}}/images/link_thumb.png @else {{url('/')}}/images/text_thumb.png @endif" alt="{{$thread->title}}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="thread_info">
                                        <a class="title" href="@if(!$thread->media_type && $thread->type === 'link') {{$thread->link}} @else {{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                            <h3>{{$thread->title}}</h3>
                                        </a>
                                        <p class="overflow description">
                                            {{ __('lang.placed-by') }}
                                            <a href="/u/{{$postername->username}}">{{$postername->username}}</a>
                                            {{Carbon\Carbon::parse($thread->created_at)->diffForHumans()}}
                                        </p>
                                        <a class="comment" href="{{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}">
                                            <p class="overflow">
                                                <strong>{{$thread->reply_count}} {{$thread->reply_count < 2 ?  __('lang.reply') :  __('lang.replies')}}</strong>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="page_control">
                            @if($page > 1 && $threads->count() > 0)
                            <a href="?page={{Request::input('page')-1}}">{{ __('lang.prev') }}</a> -
                            @endif
                            @if($threads->count() > 24)
                                <a href="@if(!(Request::input('page'))) ?page=2 @else ?page={{$page+1}} @endif">{{ __('lang.next') }}</a>
                            @endif
                        </div>

                        @php unset($thread); @endphp
                    @endif

                    @if($threads == null || $threads && $threads->count() == 0 && !Request::input('page') && !Request::input('after'))
                        <h2 id="looks_like thin text-center">{{ __('lang.no-results-found') }}</h2>
                        @php $no_res = true; @endphp
                    @elseif(Request::input('page') || Request::input('after'))
                        @if($threads == null || $threads && $threads->count() == 0 )
                            <div class="welcome thin">
                                <h2 class="thin">{{ __('lang.no-results-found-for-that-search-criteria') }}</h2>
                                <h4 class="thin text-center">{{ __('lang.looks-like-we-ran-out-of-stolen-memes') }}</h4>
                                <a href="@if(Request::input('page') == '2') /p/{{$subPabble->name}} @elseif(Request::input('after')) ?page={{Request::input('page')-1}}&after={{Request::input('after')}} @else ?page={{Request::input('page')-1}} @endif">{{ __('lang.go-back-a-page') }}</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @include('layouts.partials.loginModal')

    @else
        <div class="container">
            <p>{{ __('lang.it-looks-like-this-pabble-doesnot-exist-make-it-yours') }}</p>
        </div>
    @endif


@endsection

@section('scripts')

    @include('layouts.partials.vote')

    @include('layouts.partials.subscriptions')

    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
        $('.count').each(function() {
            _this = $(this);
            if (_this.text() > 1000) {
                _this.text(numeral(_this.text()).format('0.0a'));
            }
        });
    </script>

@endsection
