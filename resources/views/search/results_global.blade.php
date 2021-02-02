@extends('layouts.app')

@section('title') Pabble: search {{substr($q, 0, 140)}} @endsection

@php $twitter_title = 'Search pabble'; @endphp
@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
@endsection

@section('content')

    <div class="container mt-3">
        <div class="row panel panel-default mr-3 pb-3 pl-3">
            <form class="mt-7" method="GET" action="/search">
                <div id="custom-search-input">
                    <div class="input-group col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                        <div class="input-group-prepend">
                            <select class="form-control" style="display: table-cell" name="searchType">
                                <option {{ $currentSearchType === 'post' ? 'selected' : '' }} value="post">POST</option>
                                <option {{ $currentSearchType === 'pabble'? 'selected' : '' }} value="pabble">Pabble</option>
                            </select>
                        </div>
                        <input value="{{ Request::input('q') }}" type="text" name="q" class="search-query-global form-control" placeholder="Search" />
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
                        <a href="{{ $current_url.'&sort=popular' }}">POPULAR</a>
                    </li>
                    <li @if(isset(request()->query()['sort']) && request()->query()['sort'] == 'new') class="selected" @endif>
                        <a href="{{ $current_url.'&sort=new' }}">NEW</a>
                    </li>
                    <li @if(isset(request()->query()['sort']) && request()->query()['sort'] == 'top') class="selected" @endif>
                        <a href="{{ $current_url.'&sort=top' }}">TOP</a>
                    </li>
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
                            <div class="panel mb-3 pl-3">
                                <h4><a href="/p/{{$pabble->name}}">{{$pabble->name}}</a></h4>
                                <p class="-mt-3">{{$readers}} {{str_plural('subscriber', $readers)}}, this subpabble was created {{Carbon\Carbon::parse($pabble->created_at)->diffForHumans()}}</p>
                                @if($pabble->title)
                                    <p class="-mt-3">{{substr($pabble->title, 0, 140)}}</p>
                                @endif
                            </div>
                        @endforeach
                        @if($page == 1 && $subpabbles->count() > 4)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page=2&searchType=pabble">next</a>
                            </div>
                        @endif
                        @if($page == 2)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page=1&searchType=pabble">previous</a>
                                @if($threads->count() > 19)
                                    - <a href="/search?q={{$q}}&page={{$page+1}}&searchType=pabble">next</a>
                                @endif
                            </div>
                        @endif
                        @if($page > 2)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page={{$page-1}}&searchType=pabble">previous</a>
                                @if($threads->count() > 24)
                                    - <a href="/search?q={{$q}}&page={{$page+1}}&searchType=pabble">next</a>
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
                            <div class="panel mb-3">
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
                                            <a href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                                <img src="@if($thread->thumbnail !== null){{$thread->thumbnail}} @elseif($thread->link) {{url('/')}}/images/link_thumb.png @else {{url('/')}}/images/text_thumb.png @endif" alt="{{$thread->title}}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="thread_info">
                                        <a class="title" href="@if($thread->link) {{$thread->link}} @else {{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}} @endif">
                                            <h3>{{$thread->title}}</h3>
                                        </a>
                                        <p class="overflow description">
                                            placed by
                                            <a href="/u/{{$postername->username}}">{{$postername->username}}</a>
                                            {{Carbon\Carbon::parse($thread->created_at)->diffForHumans()}} in
                                            <a href="/p/{{$pabble->name}}">{{$pabble->name}}</a>
                                            (<span class="upvote"> +{{$thread->upvotes}}</span> | <span class="downvote"> -{{$thread->downvotes}}</span> )
                                        </p>
                                        <a class="comment" href="{{url('/')}}/p/{{$pabble->name}}/comments/{{$thread->code}}/{{str_slug($thread->title)}}">
                                            <p class="overflow">
                                                <strong>{{$thread->reply_count}} {{$thread->reply_count < 1 ? 'reply' : str_plural('reply', $thread->reply_count)}}</strong>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if($page == 1 && $threads->count() > 4)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page=2&searchType=post">next</a>
                            </div>
                        @endif
                        @if($page == 2)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page=1&searchType=post">previous</a>
                                @if($threads->count() > 19)
                                    - <a href="/search?q={{$q}}&page={{$page+1}}&searchType=post">next</a>
                                @endif
                            </div>
                        @endif
                        @if($page > 2)
                            <div class="mt-0 mb-3">
                                <a href="/search?q={{$q}}&page={{$page-1}}&searchType=post">previous</a>
                                @if($threads->count() > 24)
                                - <a href="/search?q={{$q}}&page={{$page+1}}&searchType=post">next</a>
                                @endif
                            </div>
                        @endif
                    </div>
                    @php unset($thread); // Unset variable so it doesn't get confused with a normal thread @endphp
                @endif

                @if($threads->count() < 1 && $subpabbles->count() < 1 && !Request::input('page') && !Request::input('after'))
                    <h2 id="looks_like" class="thin text-center mt-3">No results found</h2>
                    @php $no_res = true; @endphp
                @endif


            </div>
        </div>
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
