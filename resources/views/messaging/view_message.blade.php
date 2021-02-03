@extends('layouts.app')

@section('title')
    Pabble: {{$messages[0]['subject']}}
@endsection


@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/subpabble.css') }}">
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
@endsection

@section('content')

    <div class="container">

        <h2 class="text_wrap mt-7">{{$messages[0]['subject']}}</h2>

        <div id="msg_container" class="col-md-9">
            @foreach($messages as $pm)
                @if($user->id == $pm->user_id)
                    <div class="msg_wrapper">
                        <div class="msg_send mt-3">
                            <div class="msg_container">
                                <p>{{$pm->from}} ({{Carbon\Carbon::parse($pm->created_at)->diffForHumans()}})</p>
                                <p class="mt-3">{!! nl2br(e($pm->message)) !!}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="msg_wrapper">
                        <div class="msg_received mt-3">
                            <div class="msg_container">
                                <p>{{$pm->from}} ({{Carbon\Carbon::parse($pm->created_at)->diffForHumans()}})</p>
                                <p class="mt-3">{!! nl2br(e($pm->message)) !!}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="reply_box col-md-9 mt-7">
            <form action="" method="post">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('reply') ? ' has-error' : '' }}">
                    <textarea class="form-control mt-3" placeholder="Reply" name="reply" cols="30" rows="5"></textarea>
                    @if ($errors->has('reply'))
                        <span class="help-block">
                            <strong>{{ $errors->first('reply') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    @if($messages->currentPage() > 1)
                        <a href="{{$messages->previousPageUrl()}}">{{ __('lang.prev') }}</a>
                    @endif
                    @if($messages->currentPage() > 1 && $messages->currentPage() !== $messages->lastPage())
                        -
                    @endif
                    @if($messages->currentPage() > 0 && $messages->currentPage() !== $messages->lastPage())
                        <a href="{{$messages->nextPageUrl()}}">{{ __('lang.next') }}</a>
                    @endif
                    <input type="submit" class="btn btn-primary pull-right" value="{{ __('lang.send-reply') }}">
                </div>
            </form>
        </div>

    </div>

@endsection
