@extends('layouts.app')

@section('title')
    @if(isset($subPabble->name)) p/{{ $subPabble->name }}
    @else What happened?
    @endif
@endsection

@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    @if($subPabble)
        <style>
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
            .tabmenu{
                margin-top: -3px !important;
            }
        </style>
        @if($subPabble->custom_css)
            <link rel="stylesheet" href="{{asset('cdn/css/'.$subPabble->name.'.css')}}">
        @endif
    @endif
@endsection

@section('content')

    @if($subPabble)

        <div class="container mt-7">
            <div class="row panel panel-default">
                <div class="col-sm-3 col-sm-push-9">
                    <div class="well search_box">
                        <h4 class="overflow">Search in <a href="/p/{{$subPabble->name}}">/p/{{$subPabble->name}}</a></h4>
                        <hr>
                        <form method="GET" action="/search/{{$subPabble->name}}">
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
                        <button @if(!$subscribed) data-subscribed="no" @else data-subscribed="yes" @endif class="btn btn-default @if(!$subscribed) notsubscribed @else subscribed @endif selected subscribe mt-3 w-full text-white" id="subscribebtn">@if(!$subscribed) Subscribe @else  Unsubsribe @endif</button>
                        <a href="/p/{{$subPabble->name}}/submit" class="btn btn-primary w-full mt-3">Submit a post</a>
                    </div>

                    <div class="well">
                        <a class="cursor-pointer" data-toggle="collapse" href="#about">
                            <h4 class="overflow">About /p/{{$subPabble->name}}</h4>
                        </a>
                        <hr>
                        @if(Auth::check())
                            @if($subPabble->owner_id == Auth::user()->id)
                                <a href="/p/{{$subPabble->name}}/edit" class="btn btn-primary w-full mb-3">Edit subpabble</a>
                            @endif
                        @endif
                        <div id="about" class="panel-collapse collapse">
                            <p>{{$readers}} {{str_plural('reader', $readers)}}</p>
                            <p>{!! nl2br(e(htmlspecialchars($subPabble->description))) !!}</p>
                        </div>
                    </div>

                    <div class="well">
                        <a data-toggle="collapse" href="#mods">
                            <h4 class="overflow">Moderators for /p/{{$subPabble->name}}</h4>
                        </a>
                        <hr>
                        <div id="mods" class="panel-collapse collapse">
                            @if($moderators->count() < 1)
                                <p>There are no mods for this subpabble yet.</p>
                            @endif
                            @foreach($moderators as $moderator)
                                <a href="/u/{{$moderator->username}}">{{$moderator->username}}</a> <br>
                            @endforeach
                        </div>
                    </div>
                </div>

                @php
                    $even = true;
                    $user = new \App\Models\User();
                @endphp
                <div class="col-sm-9 col-sm-pull-3">

                    @if($threads)
                    <div class="page-info">
                        <span>
                            <span class="title">
                                /p/{{$subPabble->name}}: {{ $subPabble->title }}
                            </span>
                            <br>
                            a community for
                            {{Carbon\Carbon::parse($subPabble->created_at)->diffForHumans()}}
                            @if (isset($moderators))
                                by
                                <a href="/u/{{$moderator->username}}">
                                    {{$moderators[0]->username}}
                                </a>
                            @endif
                        </span>
                        <div class="tabmenu">
                            <li @if(!$sort || $sort == 'popular') class="selected" @endif><a href="/p/{{$subPabble->name}}">POPULAR</a></li>
                            <li @if($sort == 'new') class="selected" @endif><a href="/p/{{$subPabble->name}}/new">NEW</a></li>
                            <li @if($sort == 'top') class="selected" @endif><a href="/p/{{$subPabble->name}}/top">TOP</a></li>
                        </div>
                    </div>
                    @foreach($threads as $thread)
                        @php $postername = $user->select('username')->where('id', $thread->poster_id)->first(); @endphp

                        <div class="thread  @if($even) even @endif @php $even = !$even @endphp">
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
                                    <a href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                        <img src="@if($thread->thumbnail !== null){{$thread->thumbnail}} @elseif($thread->link) {{url('/')}}/images/link_thumb.png @else {{url('/')}}/images/text_thumb.png @endif" alt="{{$thread->title}}">
                                    </a>
                                </div>
                            </div>
                            <div class="thread_info">
                                <a class="title" href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                    <h3>{{$thread->title}}</h3>
                                </a>
                                <p class="overflow description">
                                    placed by
                                    <a href="/u/{{$postername->username}}">{{$postername->username}}</a>
                                    {{Carbon\Carbon::parse($thread->created_at)->diffForHumans()}}
                                    (<span class="upvote"> +{{$thread->upvotes}}</span> | <span class="downvote"> -{{$thread->downvotes}}</span> )
                                </p>
                                <a class="comment" href="{{url('/')}}/p/{{$subPabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}">
                                    <p class="overflow">
                                        <strong>{{$thread->reply_count}} {{$thread->reply_count < 1 ? 'reply' : str_plural('reply', $thread->reply_count)}}</strong>
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <div id="page_control">
                        @if(Request::input('page') > 1 && $threads->count() > 0)
                            <a href="?page={{Request::input('page')-1}}">Previous</a> -
                        @endif
                        @if($threads->count() > 24)
                            <a href="@if(!(Request::input('page'))) ?page=2 @else ?page={{Request::input('page')+1}} @endif">Next</a>
                        @endif
                    </div>
                    @endif
                    @php unset($thread); @endphp
                </div>

                @if($threads == null || $threads && $threads->count() == 0 && !Request::input('page') && !Request::input('after'))
                    <div class="col-sm-8 col-sm-pull-4">
                        <h2 id="looks_like" class="thin text-center">Looks like this subpabble is still empty.</h2>
                        <h4 class="thin text-center">Go <a href="/p/{{$subPabble->name}}/submit">submit</a> something awesome.</h4>
                    </div>
                    @php $no_res = true; @endphp
                @elseif(Request::input('page') || Request::input('after'))
                    @if($threads == null || $threads && $threads->count() == 0 )
                        <div class="col-sm-8 col-sm-pull-4">
                            <div class="welcome thin">
                                <h2 class="thin">No results found for that search criteria</h2>
                                <h4 class="thin text-center">Looks like we ran out of stolen memes</h4>
                                <a href="@if(Request::input('page') == '2') /p/{{$subPabble->name}} @elseif(Request::input('after')) ?page={{Request::input('page')-1}}&after={{Request::input('after')}} @else ?page={{Request::input('page')-1}} @endif">Go back a page</a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>


        </div>

        @include('layouts.partials.loginModal')

    @else
        <div class="container">
            <p class="mt-7">It looks like this pabble does not exist. Make it yours!</p>
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
        if ($(window).width() > 768) {
            $( ".collapse" ).each(function( el ) {
                $(this).addClass('in');
            });
        }
    </script>

@endsection
