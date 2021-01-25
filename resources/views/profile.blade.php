@extends('layouts.app')

@section('title')
    @if($user)
        Pabble: {{$user->username}}
    @else
    Pabble: User not found...
    @endif
@endsection

@if($user)
    @php $twitter_title =  '@' . $user->username; @endphp
@endif
@include('layouts.partials.twitter_cards')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thread.css') }}">
    <style>
        .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
            background: white;
        }
        @media screen and (max-width: 560px) {
            .profile_padding {
                margin-top: 40px;
                margin-left: -10px;
            }
        }
        #posts .warning-msg, #comments .warning-msg{
            padding-top:20px;
            padding-bottom: 20px;
            padding-left: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-3">
        @if($user)
        <ul class="tabmenu">
            <li @if($sort == 'new') class="selected" @endif><a href="/u/{{$user->username}}/new">new</a></li>
            <li @if($sort == 'popular') class="selected" @endif><a href="/u/{{$user->username}}/popular">popular</a></li>
            <li @if($sort == 'top') class="selected" @endif><a href="/u/{{$user->username}}/top">top</a></li>
        </ul>

        <div class="row profile_padding">
            <div class="col-md-12">
                <h1 class="overflow">{{$user->username}} @if(!$user->active)<span>Account not activated yet</span>@endif</h1>
            </div>
            <div id="profile" class="col-sm-4 col-md-3">
                <ul class="list-group">
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span> {{\Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans()}}</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Karma</strong></span> {{$user->thread_karma + $user->post_karma}}</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Post Karma</strong></span> {{$user->thread_karma}}</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Comment Karma</strong></span> {{$user->post_karma}}</li>
                    <li id="subs_list" class="list-group-item text-left"><span><strong>Subscribed to</strong></span>
                        @foreach($subscriptions as $sub)
                            <br><a href="/p/{{$sub->name}}">/p/{{$sub->name}}</a>
                        @endforeach
                    </li>
                    <a class="ml-1" href="{{ route('messages.send') }}/{{ $user->username }}">Message {{ $user->username }}</a>
                </ul>
            </div>

            <div class="col-sm-8 col-md-9">
                <ul class="nav nav-tabs">
                    <li @if(app('request')->input('type') == 'posts') class="active"  @elseif(empty(app('request')->input('type'))) class="active" @endif><a data-toggle="tab" href="#posts">Posts</a></li>
                    <li @if(app('request')->input('type') == 'comments') class="active" @endif><a data-toggle="tab" href="#comments">Comments</a></li>
                </ul>

                <div class="tab-content">
                    <div id="posts" class="tab-pane fade @if(app('request')->input('type') == 'posts') in active  @elseif(empty(app('request')->input('type'))) in active @endif">
                        @if($posts->count() < 1)
                            <p class="warning-msg">Sorry, ran out of posts for this user.</p>
                        @endif
                        @foreach($posts as $post)
                            @php $postername = $user->select('username')->where('id', $post->poster_id)->first(); @endphp
                            @php $subpabble = \App\Models\SubPabble::select('name')->where('id', $post->sub_pabble_id)->first(); if (!$subpabble) {$subpabble->name = 'removed'; } @endphp
                            <div class="panel mt-0 pb-3 mb-3">
                                <div class="thread">
                                    <div class="votes col-xs-1">
                                        <div class="row stack upvote">
                                            <i id="{{$post->id}}_up" data-voted="no" data-vote="up" data-thread="{{$post->code}}" class="fa fa-sort-asc vote"></i>
                                        </div>
                                        <div class="row stack">
                                            <span id="{{$post->id}}_counter" class="stack count">{{$post->upvotes - $post->downvotes}}</span>
                                        </div>
                                        <div class="row stack downvote">
                                            <i id="{{$post->id}}_down" data-voted="no" data-vote="down" data-thread="{{$post->code}}" class="fa fa-sort-desc stack vote"></i>
                                        </div>
                                    </div>
                                    <div class="image col-xs-1">
                                        <div class="row">
                                            <a href="@if($post->link) {{$post->link}} @else {{url('/')}}/p/{{$subpabble->name}}/comments/{{$post->code}}/{{$post->title}} @endif">
                                                <img src="@if($post->thumbnail !== null){{$post->thumbnail}} @elseif($post->link) {{url('/')}}/images/link_thumb.png @else {{url('/')}}/images/text_thumb.png @endif" alt="{{$post->title}}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="thread_info">
                                        <a class="title" href="@if($post->link) {{$post->link}} @else {{url('/')}}/p/{{$subpabble->name}}/comments/{{$post->code}}/{{str_slug($post->title)}} @endif">
                                            <h3>{{$post->title}}</h3>
                                        </a>
                                        <p class="overflow description">
                                            placed by
                                            <a href="/u/{{$postername->username}}">{{$postername->username}}</a>
                                            {{Carbon\Carbon::parse($post->created_at)->diffForHumans()}} in
                                            <a href="/p/{{$subpabble->name}}">{{$subpabble->name}}</a>
                                            (<span class="upvote"> +{{$post->upvotes}}</span> | <span class="downvote"> -{{$post->downvotes}}</span> )
                                        </p>
                                        <a class="comment" href="{{url('/')}}/p/{{$subpabble->name}}/comments/{{$post->code}}/{{$post->title}}">
                                            <p class="overflow">
                                                <strong>{{$post->reply_count}} {{$post->reply_count < 1 ? 'reply' : str_plural('reply', $post->reply_count)}}</strong>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if(Request::input('page') > 1)
                            <a href="?page={{Request::input('page')-1}}&type=posts">Previous</a> -
                        @endif
                        @if($posts->count() > 24)
                            @if(Request::input('page') !== null || !empty(Request::input('page')))
                                <a href="?page={{$page + 1}}&type=posts">Next</a>
                            @else
                                <a href="?page=2&type=posts">Next</a>
                            @endif
                        @endif
                    </div>

                    <div id="comments" class="tab-pane fade @if(app('request')->input('type') == 'comments') in active @endif">
                        @if($comments->count() < 1)
                            <p class="warning-msg">Sorry, ran out of posts for this user.</p>
                        @endif
                        @foreach($comments as $comment)
                            @php $thread = \App\Models\Thread::select('code', 'sub_pabble_id')->where('id', $comment->thread_id)->first(); if(!$thread) { $thread->code = 'removed'; } @endphp
                            @php $subpabble = \App\Models\SubPabble::select('name')->where('id', $thread->sub_pabble_id)->first(); if (!$subpabble) {$subpabble->name = 'removed'; } @endphp
                            <div id="post_panel_{{$comment->id}}" class="col-xs-12 panel comment mb-3">
                                <div class="row thread">
                                    <div class="votes col-xs-2 col-sm-1 mt-0">
                                        <div class="row stack upvote mt-0">
                                            <a href="javascript:votepost('{{$comment->id}}', `up`);"><i id="{{$comment->id}}_up_post" data-voted="no" data-vote="up" data-post="{{$comment->id}}" class="fa fa-sort-asc vote"></i></a>
                                        </div>
                                        <div class="row stack">
                                            <span id="{{$comment->id}}_counter_post" class="stack">{{$comment->score}}</span>
                                            </div>
                                        <div class="row stack downvote">
                                            <a href="javascript:votepost('{{$comment->id}}', `down`);"><i id="{{$comment->id}}_down_post" data-voted="no" data-vote="down" data-thread="{{$comment->id}}" class="fa fa-sort-desc stack vote"></i></a>
                                            </div>
                                        </div>
                                    <div class="col-xs-10 col-sm11">
                                        <span>
                                            <a href="/u/{{$comment->user_display_name}}">{{$comment->user_display_name}}</a>
                                            {{Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}
                                        </span>
                                        <p>{!! nl2br($comment->comment) !!}</p>
                                        <div class="linkwrapper"><a href="/p/{{$subpabble->name}}/comments/{{$thread->code}}">thread</a></div>
                                        <div id="comment_box_app_{{$comment->id}}"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if(Request::input('page') > 1)
                            <a href="?page={{Request::input('page')-1}}&type=comments">Previous</a> -
                        @endif
                        @if ($comments->count() > 24)
                            @if(Request::input('page') !== null || !empty(Request::input('page')))
                                <a href="?page={{$page + 1}}&type=comments">Next</a>
                            @else
                                <a href="?page=2&type=comments">Next</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @else
            <h1 class="thin">This user does not exist</h1>
        @endif
    </div>

    @include('layouts.partials.loginModal')


@endsection

@section('scripts')

    @include('layouts.partials.vote')

    <script src="{{asset('js/jquery.sticky-kit.min.js')}}"></script>
    <script>
        if ($(window).width() > 768) {
            $("#profile").stick_in_parent({
                offset_top: 30
            });
            slist = $('#subs_list');
            offset = window.innerHeight - slist.offset().top;
            slist.css('max-height', offset-20 + 'px');
        }
    </script>

    <script>
        @if(Auth::check() && $userVotes)
            @foreach($userVotes as $vote)
                @if($vote->vote == 1 && $vote->post_id)
                    $('#{{$vote->post_id}}_up_post').css('color', '#4CAF50').attr('data-voted', 'yes');
                @elseif($vote->post_id)
                    $('#{{$vote->post_id}}_down_post').css('color', '#F44336').attr('data-voted', 'yes');
                @endif
            @endforeach
        @endif

    </script>

@endsection
