<x-guest-layout>
    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" /> --}}

    <x-slot name="header">
        {{ __('Sign In') }}
    </x-slot>

    <form class="js-validation-signin" method="POST" action="{{ route('login_unimas.store') }}">
        @csrf
        <div class="mb-4">
            <div class="input-group input-group-lg">
                <input id="username" type="text" class="form-control" name="username" autofocus placeholder="Username">
                <span class="input-group-text">
                    <i class="fa fa-user-circle"></i>
                </span>
            </div>
        </div>
        <div class="mb-4">
            <div class="input-group input-group-lg">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="Password">
                <span class="input-group-text">
                    <i class="fa fa-asterisk"></i>
                </span>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-start mb-4">
            {{-- <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="login-remember-me">Remember Me</label>
            </div> --}}

            <div class="fw-semibold fs-sm py-1">
                {{-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        Forgot Password?</a>
                    </a>
                @endif --}}
            </div>
        </div>
        <div class="text-center mb-4">
            <button type="submit" class="btn btn-hero btn-primary">
                <i class="fa fa-fw fa-sign-in-alt opacity-50 me-1"></i> Sign In
            </button>
        </div>
        <div class="text-center">
            {{-- <p>Don't have an account? <span style="color: #FFC107;"><a href="{{ route('register') }}">Sign Up</a></span> --}}
            </p>
        </div>
    </form>

</x-guest-layout>
