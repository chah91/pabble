@extends('layouts.app')

@section('title') Pabble: Post stolen memes here @endsection

@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
@endsection

@section('content')

    <div class="container panel panel-default" style="margin-top: 15px; width: 80%;">
        <div class="row">
            <div class="col-sm-3 col-sm-push-9">
                <div style="padding-top: 10px;padding-bottom: 20px;background:#fafafa" class="well search_box">
                    <h4 style="color:#3097D1;margin-bottom:3px;">Search Pabble</h4>
                    <hr style="margin-top: 3px;margin-bottom:7px;border-top:1px solid #b6b6b6">
                    <form method="GET" action="/search">
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="text" name="q" class="search-query form-control" placeholder="Search" />
                                <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">
                                            <span class="fa fa-search"></span>
                                        </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <a href="/submit?type=link" class="btn btn-primary" style="width:100%">Share a link</a>
                <a href="/submit?type=text" class="btn btn-primary" style="width:100%;margin-top:15px">Discuss</a>
                <a href="/subpabbles/create" class="btn btn-default" style="width:100%;margin-top:15px">Create your own subpabble</a>

                <div style="padding-top: 10px;padding-bottom: 20px;background:#fafafa" class="well search_box">
                    <h5 style="color:#3097D1;margin-bottom:3px;">Most viewed in the past 24 hours</h5>
                    <hr style="margin-top: 3px;margin-bottom:7px;border-top:1px solid #b6b6b6">
                    @php $last_threads = \App\Models\Thread::getLastViewdThreadsLast24Hours(); @endphp
                    @foreach ($last_threads as $thread)
                        @php $pabble = \App\Models\SubPabble::select('id', 'name')->where('id', $thread->sub_pabble_id)->first();@endphp
                        <a href="{{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}">{{$thread->title}}</a><br>
                        {{$pabble->name}} / {{Carbon\Carbon::parse($thread->updated_at)->diffForHumans()}} <br>
                    @endforeach
                </div>
            </div>

            @php
                $user = new \App\Models\User();
                $even = true;
            @endphp
            <div class="col-sm-9 col-sm-pull-3">
                <div class="page-info">
                    <span class="title">
                        HOME
                    </span>
                    <div class="tabmenu">
                        <li @if(!$sort || $sort == 'popular') class="selected" @endif><a href="/">POPULAR</a></li>
                        <li @if($sort == 'new') class="selected" @endif><a href="/s/new">NEW</a></li>
                        <li @if($sort == 'top') class="selected" @endif><a href="/s/top">TOP</a></li>
                    </div>
                </div>
                @if($threads)
                @foreach($threads as $thread)
                    @php $postername = $user->select('username')->where('id', $thread->poster_id)->first(); @endphp
                    @php $pabble = \App\Models\SubPabble::select('id', 'name')->where('id', $thread->sub_pabble_id)->first();@endphp

                    <div class="thread @if($even) even @endif @php $even = !$even @endphp">
                        <div style="min-width: 40px;border-right: 2px solid #3097d1;margin-right:10px;padding-right:10px;margin-top:6px;" class="votes col-xs-1">
                            <div style="margin-bottom: -15px;margin-top:8px" class="row stack">
                                <i id="{{$thread->id}}_up" data-voted="no" data-vote="up" data-thread="{{$thread->code}}" class="fa fa-sort-asc vote"></i>
                            </div>
                            <div class="row stack">
                                <span id="{{$thread->id}}_counter" class="stack count">{{$thread->upvotes - $thread->downvotes}}</span>
                            </div>
                            <div style="margin-top: -15px;" class="row stack">
                                <i id="{{$thread->id}}_down" data-voted="no" data-vote="down" data-thread="{{$thread->code}}" class="fa fa-sort-desc stack vote"></i>
                            </div>
                        </div>
                        <div style="min-width: 90px;margin-top:10px;" class="image col-xs-1">
                            <div class="row">
                                <a href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif"><img style="max-height: 76px; max-width: 76px;" src="@if($thread->thumbnail !== null){{$thread->thumbnail}} @elseif($thread->link) {{url('/')}}/images/link_thumb.png @else {{url('/')}}/images/text_thumb.png @endif" alt="{{$thread->title}}"></a>
                            </div>
                        </div>
                        <div class="thread_info">
                            <a style="color: #636b6f;" href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif"><h3 class="thread_title">{{$thread->title}}</h3></a>
                            <p class="overflow" style="margin-top: -10px;">placed by <a href="/u/{{$postername->username}}">{{$postername->username}}</a> {{Carbon\Carbon::parse($thread->created_at)->diffForHumans()}} in
                            <a href="/p/{{$pabble->name}}">{{$pabble->name}}</a> (<span class="upvote"> +{{$thread->upvotes}}</span> | <span class="downvote"> -{{$thread->downvotes}}</span> )</p>
                            <a href="{{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}"><p class="overflow" style="margin-top: -10px;"><strong>{{$thread->reply_count}} {{$thread->reply_count < 1 ? 'comment' : str_plural('comment', $thread->reply_count)}}</strong></p></a>
                        </div>
                    </div>
                @endforeach
                @endif
            </div>
            @php unset($thread); // Unset variable so it doesn't get confused with a normal thread @endphp
        @if($threads == null || $threads && $threads->count() == 0 && !Request::input('page') && !Request::input('after'))
            <div class="col-sm-8 col-sm-pull-4">
                <div class="welcome" style="font-weight: lighter; margin-top: 50px; text-align: center">
                    <h2 style="font-weight: lighter">@if(Auth::check()) <strong>{{Auth::user()->username}},</strong> @endif this is your homepage</h2>
                    <h4 style="font-weight: lighter; text-align: center">Fill it up by subscribing to some subpabbles</h4>
                    <p style="margin-top: 50px;">Find some communities by searching or...</p>
                </div>
                <div onclick="window.location.href='/g/popular'" style="display: block; margin-left: auto;  margin-right: auto; width:210px;" class="btn btn-primary">Check out what's popular</div>
            </div>
            @php $no_res = true; @endphp
        @elseif(Request::input('page') || Request::input('after'))
            @if($threads == null || $threads && $threads->count() == 0 )
                <div class="col-sm-8 col-sm-pull-4">
                    <div class="welcome" style="font-weight: lighter; margin-top: 50px; text-align: center">
                        <h2 style="font-weight: lighter">No results found for that search criteria</h2>
                        <h4 style="font-weight: lighter; text-align: center">Looks like we ran out of stolen memes</h4>
                        <a href="@if(Request::input('page') == '2') / @elseif(Request::input('after')) ?page={{Request::input('page')-1}}&after={{Request::input('after')}} @else ?page={{Request::input('page')-1}} @endif">Go back a page</a>
                    </div>
                </div>
                @php $no_res = true; @endphp
            @endif
        @endif

        @if(!isset($no_res))
            <div  id="page_control">
                @if(Request::input('page') > 1)
                    <a href="?page={{Request::input('page')-1}}">Previous</a> -
                @endif
                @if($threads->count() > 24)
                    <a href="@if(!(Request::input('page'))) ?page=2 @else ?page={{$page+1}} @endif">Next</a>
                @endif
            </div>
        @endif

    </div>

    @include('layouts.partials.loginModal')

@endsection

@section('scripts')
    @include('layouts.partials.vote')
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
