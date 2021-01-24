@extends('layouts.app')

@section('title') Pabble: search {{substr($q, 0, 140)}} @endsection

@php $twitter_title = 'Search pabble'; @endphp
@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
@endsection

@section('content')
    <div id="stripe" data-spy="affix"></div>

    <div class="container">
        <form style="margin-top: 20px;" method="GET" action="/search">
            <div id="custom-search-input">
                <div class="input-group col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <input value="{{ Request::input('q') }}" type="text" name="q" class="search-query form-control" placeholder="Search" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">
                            <span class="fa fa-search"></span>
                        </button>
                    </span>
                </div>
            </div>
        </form>

        @php
            $first = true;
            $user = new \App\Models\User();
        @endphp
        <div class="row">
            @if($subpabbles->count() > 0)
                <div class="col-sm-12">
                    <h3>Subpabbles</h3>
                    @foreach($subpabbles as $pabble)
                        @php $readers = \App\Models\Subscription::where('sub_pabble_id', $pabble->id)->count(); @endphp
                        <div style="padding-left: 10px; margin-bottom: 10px;" class="panel">
                            <h4><a href="/p/{{$pabble->name}}">{{$pabble->name}}</a></h4>
                            <p style="font-size: 12px;">{{$readers}} {{str_plural('subscriber', $readers)}}, this subpabble was created {{Carbon\Carbon::parse($pabble->created_at)->diffForHumans()}}</p>
                            @if($pabble->title)
                                <p style="margin-top: -10px; font-size: 12px;">{{substr($pabble->title, 0, 140)}}</p>
                            @endif
                        </div>
                    @endforeach
                    @if($page == 1 && $subpabbles->count() > 4)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page=2&type=subpabbles">next</a>
                        </div>
                    @endif
                    @if($page == 2)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page=1">previous</a>
                            @if($threads->count() > 19)
                                - <a href="/search?q={{$q}}&page={{$page+1}}&type=subpabbles">next</a>
                            @endif
                        </div>
                    @endif
                    @if($page > 2)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page={{$page-1}}&type=subpabbles">previous</a>
                            @if($threads->count() > 24)
                                - <a href="/search?q={{$q}}&page={{$page+1}}&type=subpabbles">next</a>
                            @endif
                        </div>
                    @endif
                </div>
            @endif


            @if($threads->count() > 0)
                <div class="col-sm-12">
                    <h3>Threads</h3>
                    @foreach($threads as $thread)
                        @php $postername = $user->select('username')->where('id', $thread->poster_id)->first(); @endphp
                        @php $pabble = \App\Models\subPabble::select('id', 'name')->where('id', $thread->sub_pabble_id)->first(); @endphp
                        <div style="margin-top: 10px;" class="panel">
                            <div class="thread">
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
                                    <a style="color: #636b6f;" href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif"><h3 class="thread_title overflow">{{$thread->title}}</h3></a>
                                    <p class="overflow" style="margin-top: -10px;">placed by <a href="/u/{{$postername->username}}">{{$postername->username}}</a> {{Carbon\Carbon::parse($thread->created_at)->diffForHumans()}} in
                                        <a href="/p/{{$pabble->name}}">{{$pabble->name}}</a> (<span class="upvote"> +{{$thread->upvotes}}</span> | <span class="downvote"> -{{$thread->downvotes}}</span> )</p>
                                    <a href="{{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}"><p class="overflow" style="margin-top: -10px;"><strong>{{$thread->reply_count}} {{str_plural('reply', $thread->reply_count)}}</strong></p></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($page == 1 && $threads->count() > 4)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page=2&type=posts">next</a>
                        </div>
                    @endif
                    @if($page == 2)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page=1">previous</a>
                            @if($threads->count() > 19)
                                - <a href="/search?q={{$q}}&page={{$page+1}}&type=posts">next</a>
                            @endif
                        </div>
                    @endif
                    @if($page > 2)
                        <div style="margin-top: 0; margin-bottom: 10px;">
                            <a href="/search?q={{$q}}&page={{$page-1}}&type=posts">previous</a>
                            @if($threads->count() > 24)
                            - <a href="/search?q={{$q}}&page={{$page+1}}&type=posts">next</a>
                            @endif
                        </div>
                    @endif
                </div>
                @php unset($thread); // Unset variable so it doesn't get confused with a normal thread @endphp
            @endif

            @if($threads->count() < 1 && $subpabbles->count() < 1 && !Request::input('page') && !Request::input('after'))
                <h2 id="looks_like" style="margin-top:15px; font-weight: lighter; text-align: center">No results found</h2>
                @php $no_res = true; @endphp
            @endif


        </div>

        @include('layouts.partials.loginModal')

        @endsection

        @section('scripts')
            <script>
                $('#stripe').affix({
                    offset: {
                        top: $('#nav').height()
                    }
                });
            </script>
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
