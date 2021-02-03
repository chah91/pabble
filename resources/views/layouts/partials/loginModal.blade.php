@if(!Auth::check())
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">{{ __('lang.please-login-or-register') }} <span id="loginModalMessage">{{ __('lang.up-vote') }}</span></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4>{{ __('lang.login') }}</h4>
                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <input id="username" type="text" class="form-control" name="username" placeholder="{{ __('lang.username') }}" required autofocus>
                                <input id="password" type="password" class="form-control mt-3" name="password" placeholder="{{ __('lang.password') }}" required>
                                <input type="hidden" name="redirect" value="{{Request::url()}}">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ __('lang.remember-me') }}
                                    </label>
                                </div>
                                <input type="submit" value="{{ __('lang.login') }}" class="btn btn-primary pull-right">
                            </form>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <h4 class="text-capitalize">{{ __('lang.register') }}</h4>
                            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}
                                <input id="name" type="text" class="form-control" name="username" placeholder="{{ __('lang.username') }}" required autofocus>
                                <input id="email" type="email" class="form-control mt-3" name="email" placeholder="{{ __('lang.email') }}" required>
                                <input id="password" type="password" class="form-control mt-3" name="password" placeholder="{{ __('lang.password') }}" required>
                                <input id="password-confirm" type="password" class="form-control mt-3" placeholder="{{ __('lang.verify-password') }}" name="password_confirmation" required>
                                <input type="submit" value="{{ __('lang.register') }}" class="btn btn-primary pull-right mt-3 text-capitalize">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
