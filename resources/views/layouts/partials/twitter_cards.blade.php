@section('meta')
    @if(isset($thread) && $thread->media_type == 'image')
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="@if(isset($thread)){{$thread->link}}@endif" />
    @else
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:image" content="{{url('/')}}/images/logo.png">
    @endif
    <meta property="twitter:site" content="@pabble">
    <meta name="twitter:description" content="@if(isset($subPabble->description_social) && !empty($subPabble->description_social)){{$subPabble->description_social}}@else {{ __('lang.the-only-place-where-stealing-memes-is-legal') }} @endif" />
    <meta name="description" content="@if(isset($subPabble->description_social) && !empty($subPabble->description_social)){{$subPabble->description_social}}@else {{ __('lang.the-only-place-where-stealing-memes-is-legal-description') }} @endif" />

    @if(isset($thread->title) && !empty($thread->title))
        <meta property="twitter:title" content="@php echo substr($thread->title, 0, 47); @endphp @if(strlen($thread->title > 47))...@endif • /p/@if(isset($subPabble)){{$subPabble->name}}@endif">
    @elseif(isset($subPabble->name) && !empty($subPabble->name))
        <meta property="twitter:title" content="Pabble • /p/@if(isset($subPabble)){{$subPabble->name}}@endif">
    @elseif(isset($twitter_title) && !empty($twitter_title))
        <meta property="twitter:title" content="Pabble • {{$twitter_title}}">
    @else
        <meta property="twitter:title" content="Pabble • {{ __('lang.post-your-stolen-memes-here') }}">
    @endif
@endsection
