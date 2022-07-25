@extends('layouts.app')

@section('content')
    <div class=" tw__w-screen tw__h-screen tw__flex tw__items-center tw__justify-center tw__flex-col">
        <h1 class=" tw__text-3xl tw__mb-1">{{ env('APP_NAME') }}</h1>
        @if (Route::has('login') || Route::has('register'))
            <div class="auth btn-group">
                @if (\Auth::check())
                    <a href="{{ route('s.index') }}" class="btn btn-sm btn-primary">Dashboard</a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-sm btn-secondary">Register</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login</a>
                    @endif
                @endif
            </div>
        @endif

        @if (\File::exists('assets/img/brand/siaji-logo.svg'))
            <span class=" tw__flex tw__items-center tw__gap-1 tw__mt-5">Powered by<a href="https://siaji.com" target="_blank"><img src="{{ asset('assets/img/brand/siaji-logo.svg') }}" class=" tw__h-3" alt="SIAJI"></a></span>
        @endif
    </div>
@endsection