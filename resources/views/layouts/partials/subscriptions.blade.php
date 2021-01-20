@if($subPabble)
<script>
    $('.subscribe').click(function() {
        subscriptions = $('.subscriptions');

        _this = $(this);
        var subscribed = _this.attr('data-subscribed');

        @if(Auth::check())
            data = {'api_token': '{{Auth::user()->api_token}}'};

            pabble = '{{$subPabble->name}}';
            if (subscribed === 'no') {
                $.post( "/api/subscribe/" + pabble, data, function( res ) {
                    _this.removeClass('notsubscribed').addClass('subscribed').attr('data-subscribed', 'yes').text('Unsubscribe');
                    subscriptions.append('<a href="/p/'+ res.sub_pabble +'">'+ res.sub_pabble +'</a>');
                });
            } else {
            $.post( "/api/unsubscribe/" + pabble, data, function( res ) {
                    _this.removeClass('subscribed').addClass('notsubscribed').attr('data-subscribed', 'no').text('Subscribe');
                    $('.sub').each(function() {
                        if ($(this).text() === res.sub_pabble) {
                            $(this).remove();
                        }
                    });
                });
            }
        @else
            $('#loginModal').modal('show');
            $('#loginModalMessage').html('to subscribe to <a href="/p/{{$subPabble->name}}">/p/{{$subPabble->name}}</a>');
        @endif
    });
</script>
@endif