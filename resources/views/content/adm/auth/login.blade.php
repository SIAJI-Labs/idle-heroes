@extends('layouts.app', [
    'wauth' => true,
    'wsecond_title' => 'Admin Login'
])

@section('content')
    <p class="text-center tw__mb-4">
        <a href="{{ route('public.index') }}" class="d-flex align-items-center justify-content-center tw__text-white">
            <i class="fa-solid fa-angle-left"></i>
            <span class="ms-2">Back to Public Page</span>
        </a>
    </p>
    <div class="card">
        <div class="row align-items-center text-center">
            <div class="col-md-12">
                <div class="card-body">
                    <h1 class="h3">Sign In</h1>

                    @if (\Session::has('status') && !(\Session::get('status')))
                        <div class="alert {{ \Session::get('alert_type') ?? 'alert-info' }} alert-dismissible fade show" role="alert">
                            <strong>Attention!</strong> {{ \Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ route('adm.login') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input class="form-control @error('email') is-invalid @enderror" id="input-email" name="email" placeholder="Username / Email" type="text" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback tw__block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input class="form-control @error('password') is-invalid @enderror" id="input-password" name="password" placeholder="Password" type="password" required>
                            @error('password')
                                <span class="invalid-feedback tw__block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group text-start mt-2">
                            <div class="form-check tw__flex tw__items-center">
                                <input class="form-check-input tw__mr-2" type="checkbox" name="remember" id="input-remember">
                                <label class="form-check-label tw__mb-0 tw__leading-none" for="input-remember">Remember me</label>
                            </div>
                        </div>
                        <br/>
                        <button type="submit" name="login" class="btn btn-block btn-primary mb-4">Sign In</button>
                    </form>

                    @if (Route::has('register'))
                        <p class="mb-0 text-muted">Don’t have an account? <a href="{{ route('register') }}" class="f-w-400">Sign Up</a></p>
                    @endif
                
                    @if (asset('assets/img/brand/Dashboardkit-logo.svg'))
                        <div class=" tw__mt-4">
                            <span>Theme by</span>
                            <img src="{{ asset('assets/img/brand/Dashboardkit-logo.svg') }}" alt="" class="img-fluid tw__h-9 tw__m-auto">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection