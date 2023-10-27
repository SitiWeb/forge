{{-- resources/views/auth/register.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('directadmin.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="host">{{ __('host') }}</label>
                            <input id="host" type="text" class="form-control" name="host" value="{{ old('host') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="port">{{ __('port') }}</label>
                            <input id="port" type="text" class="form-control" name="port" value="{{ old('port') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="username">{{ __('username') }}</label>
                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('password') }}</label>
                            <input id="password" type="text" class="form-control" name="password" value="{{ old('password') }}" required autofocus>
                        </div>

                        

                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
