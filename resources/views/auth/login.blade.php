<x-guest-layout>
    <!-- Add Background Style -->
    <style>
        .login-background {
            background-image: url('{{ asset('images/background.jpg') }}');
            background-size: cover;
            background-position: center;
            min-height: 100vh; /* Adjust as needed */
        }
    </style>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="login-background d-flex justify-content-center align-items-center">
        <div class="hero-static col-lg-6 col-xl-4">
            <div class="content content-full overflow-hidden">
                <!-- Header -->
                <div class="py-4 text-center">
                    <a class="link-fx fw-bold" href="{{ route('login') }}">
                        <img src="{{ asset('images/logo1_pakarti.png') }}" width="150px"/>
                    </a>
                    <h1 class="h3 fw-bold mb-2 text-white">Selamat Datang</h1>
                    <h2 class="h5 fw-medium text-white mb-0">Silahkan Login Terlebih Dahulu!</h2>
                </div>
                <!-- END Header -->

                <!-- Sign In Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="block block-themed block-rounded">
                        <div class="block-content">
                            <div class="mb-4">
                                <label class="form-label" for="val-email">NIP / Username
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="val-email" name="email" placeholder="Masukan Username">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="val-password">Password
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="val-password" name="password" placeholder="Masukan password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="row">
                                <div class="col-sm-6 d-sm-flex align-items-center push">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="login-remember-me"
                                            name="login-remember-me">
                                        <label class="form-check-label" for="login-remember-me">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end push">
                                    <button type="submit" class="btn btn-lg btn-alt-primary fw-medium">
                                        Login Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END Sign In Form -->
            </div>
        </div>
    </div>

    {{-- <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="block block-themed block-rounded block-fx-shadow">
            <div class="block-header bg-gd-dusk">
                <h3 class="block-title">Silahkan Login Terlebih Dahulu!</h3>
            </div>
            <div class="block-content">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" id="login-username" name="login-username"
                        placeholder="Enter your username">
                    <label class="form-label" for="login-username">Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="login-password" name="login-password"
                        placeholder="Enter your password">
                    <label class="form-label" for="login-password">Password</label>
                </div>
                <div class="row">
                    <div class="col-sm-6 d-sm-flex align-items-center push">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="login-remember-me"
                                name="login-remember-me">
                            <label class="form-check-label" for="login-remember-me">Remember Me</label>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end push">
                        <button type="submit" class="btn btn-lg btn-alt-primary fw-medium">
                            Sign In
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full bg-body-light text-center d-flex justify-content-between">
                <a class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block" href="op_auth_signup3.html">
                    <i class="fa fa-plus opacity-50 me-1"></i> Create Account
                </a>
                <a class="fs-sm fw-medium link-fx text-muted me-2 mb-1 d-inline-block" href="op_auth_reminder3.html">
                    Forgot Password
                </a>
            </div>
        </div> --}}
        {{-- <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div> --}}
    {{-- </form> --}}
</x-guest-layout>
