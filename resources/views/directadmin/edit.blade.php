{{-- resources/views/directadmin/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit DirectAdmin Credentials') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('directadmin.update', $directadminCredential->id) }}">
                        @csrf
                        @method('PUT') {{-- Use the PUT method for updates --}}

                        <div class="form-group">
                            <label for="host">{{ __('Host') }}</label>
                            <input id="host" type="text" class="form-control" name="host" value="{{ old('host', $directadminCredential->host) }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="port">{{ __('Port') }}</label>
                            <input id="port" type="text" class="form-control" name="port" value="{{ old('port', $directadminCredential->port) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="username">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username', $directadminCredential->username) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="text" class="form-control" name="password" value="{{ old('password', $directadminCredential->password) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
