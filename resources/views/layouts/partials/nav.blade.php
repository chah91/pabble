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
                            {{ __('lang.my-subpabbles') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="subscriptions">
                                @if($subscribed->count() < 1)
                                    <span>{{ __('lang.no-subscribtion-yet') }}</span>
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
            <a href="/">{{ __('lang.home') }}</a>
            <a href="/">{{ __('lang.popular') }}</a>
            <a href="/">{{ __('lang.all') }}</a>
        </div>
        @php
        $allSubPabbles = \App\Models\SubPabble::get();
        @endphp
        <div class="all-subpabbles">
            @foreach ($allSubPabbles as $item)
                <a href="/p/{{$item->name}}">{{$item->name}}</a>
            @endforeach
        </div>
        <a href="/subpabbles" class="pl-3 mr-3">{{ __('lang.more') }}&nbsp;»</a>
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
                <a href="/submit?type=link" class="btn btn-primary">{{ __('lang.submit-link') }}</a>
                <a href="/submit?type=text" class="btn btn-primary">{{ __('lang.submit-post') }}</a>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            @php
                $countries = trans('countries');
                $locale = \App::currentLocale();
            @endphp
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <div class="join-nav">
                        {{ __('lang.want-to-join') }} <a href="{{ route('login') }}">{{ __('lang.login') }}</a> {{ __('lang.or') }} <a href="{{ route('register') }}">{{ __('lang.register') }}</a> {{ __('lang.in-seconds') }}
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
                                            <span><strong>{{$alert['user_display_name']}}</strong> {{ __('lang.replied') }} <strong>{{substr($alert['comment'], 0, 43)}}</strong> {{ __('lang.on') }} {{substr($alert['thread_title'], 0, 20)}}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('message.view', $alert['code']) }}">
                                            <span>{{ __('lang.new-private-message-from') }} <strong>{{$alert['user_display_name']}}</strong></span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                            @if(count($alerts) < 1)
                                <li>
                                    <a>
                                        {{ __('lang.no-new-alerts') }}
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
                            <li><a href="/u/{{Auth::user()->username}}">{{ __('lang.my-profile') }}</a></li>
                            <li><a href="{{ route('messages.inbox') }}">{{ __("lang.private-messages") }}</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __("lang.logout") }}</a></li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST">{{ csrf_field() }}</form>
                        </ul>
                    </li>
                @endif
            </ul>
            <select class="form-control lang-select" name="lang" onchange="changeLang(this)">
                @foreach(trans('countries') AS $key => $label)
                <option {{ $locale === $key ? 'selected' : '' }} value={{ $key }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</nav>
<script>
    function changeLang(target){
        let lang = target.value
        $.ajax({
            url: "/setLang?lang=" + lang,
            type:"POST",
            data:{
                _token : $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if (response.success){
                    window.location.reload()
                }
            }
        })
    }
</script>
