<nav id="nav" class="navbar navbar-default navbar-static-top">
    <div class="pabble-topbar">
        @if(Auth::check())
        <div class="my-subpabbles">
                @php
                    $subscriptions = new \App\Models\Subscription();
                    $subscribed = $subscriptions->subscriptions(Auth::user()->id);
                @endphp
                <ul class="nav navbar">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            MY SUBPABBLES <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="subscriptions">
                                @if($subscribed->count() < 1)
                                    <span>No subscriptions yet</span>
                                @else
                                    @foreach($subscribed as $sub)
                                        <a class="sub" href="/p/{{$sub->name}}">{{$sub->name}}</a>
                                    @endforeach
                                @endif
                            </li>
                        </ul>
                    </li>
                </ul>

        </div>
        @endif
        <div class="main-links">
            <a href="/">HOME</a>
            <a href="/">POPULAR</a>
            <a href="/">ALL</a>
        </div>
        @php
        $allSubPabbles = \App\Models\SubPabble::get();
        @endphp
        <div class="all-subpabbles">
            @foreach ($allSubPabbles as $item)
                <a href="/p/{{$item->name}}">{{$item->name}}</a>
            @endforeach
        </div>
    </div>
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            @if(Auth::check())
                @php
                    $alerts = new \App\Models\Alert();
                    $alerts = $alerts->getAlertsByUser(Auth::user()->id);
                @endphp
                <span id="alerts_mobile" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="fa fa-bell"></span>
                        @if(count($alerts) > 0)<span class="badge">{{count($alerts)}}</span>@endif
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @php $first = true; @endphp
                        @foreach($alerts as $alert)
                            <li>
                                @if(!$first)
                                    <hr>
                                @endif
                                @php $first = false; @endphp
                                @if($alert['type'] == 'mention')
                                    <a class="mention" href="/alerts/{{$alert['code']}}">
                                        <span><strong>{{$alert['user_display_name']}}</strong> replied <strong>{{substr($alert['comment'], 0, 43)}}</strong> on {{substr($alert['thread_title'], 0, 20)}}</span>
                                    </a>
                                @else
                                    <a href="{{ route('message.view', $alert['code']) }}">
                                        <span>New private message from <strong>{{$alert['user_display_name']}}</strong></span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                        @if(count($alerts) < 1)
                            <li>
                                <a>
                                    No alerts for now
                                </a>
                            </li>
                        @endif
                    </ul>
                </span>
            @endif

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <div>
                    @if(isset($subPabble) && $subPabble->icon)
                        <img src="/images/pabbles/icons/{{$subPabble->icon}}" alt="pabble">
                    @else
                        <img src="/images/logo.png" alt="pabble">
                    @endif
                </div>
            </a>
            <div class="flex pubble-manage">
                <a href="/submit?type=link" class="btn btn-primary">Share a link</a>
                <a href="/submit?type=text" class="btn btn-primary">Discuss</a>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <div class="join-nav">
                        Want to join? <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">register</a> in seconds
                    </div>
                @else
                    <li id="alerts_desktop" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="fa fa-bell"></span>
                            @if(count($alerts) > 0)<span class="badge">{{count($alerts)}}</span>@endif
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            @php $first = true; @endphp
                            @foreach($alerts as $alert)
                                <li>
                                    @if(!$first)
                                    <hr>
                                    @endif
                                    @php $first = false; @endphp
                                    @if($alert['type'] == 'mention')
                                        <a class="mention" href="/alerts/{{$alert['code']}}">
                                            <span><strong>{{$alert['user_display_name']}}</strong> replied <strong>{{substr($alert['comment'], 0, 43)}}</strong> on {{substr($alert['thread_title'], 0, 20)}}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('message.view', $alert['code']) }}">
                                            <span>New private message from <strong>{{$alert['user_display_name']}}</strong></span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                            @if(count($alerts) < 1)
                                <li>
                                    <a>
                                        There are no new alerts
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/u/{{Auth::user()->username}}">My profile</a></li>
                            <li><a href="{{ route('messages.inbox') }}">Private messages</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST">{{ csrf_field() }}</form>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
