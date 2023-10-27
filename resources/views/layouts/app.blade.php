<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Include jQuery from a CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap CSS from a CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap JavaScript from a CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @foreach($navItems as $item)
                            <li class="nav-item px-3"><a href="{{env('APP_URL')}}{{ $item->url }}">{{ $item->title }}</a></li>
                        @endforeach
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @include('toast')
            @yield('content')
        </main>
    </div>
    <script>
    function getDeploymentLog(e){
        var serverId = jQuery(e).data('server-id');
        var siteId = jQuery(e).data('site-id');
        var deploymentId = jQuery(e).data('deployment-id');
        var target = jQuery(e).data('bs-target');
    
        jQuery.ajax({
                  url: `{{env("APP_URL")}}/data/sites/deployment/${serverId}/${siteId}/${deploymentId}`, 
                  type: 'GET',
                  // data: { cardId: cardId }, // Send the card ID to the server
                  success: function (data) {
                      // Update the card content with the loaded data
                      jQuery(target + ' .accordion-body' ).html(data.output) ;
                  },
                  error: function () {
                      // Handle AJAX errors if needed
                  }
              });
      }

    </script>
      @isset($server)
      @isset($website)
      
      <script>
        let data = {}; // Define data in a higher scope
        let intervalId; 
    
        function getLastConsole(commandId) {
            jQuery.ajax({
                url: `{{env("APP_URL")}}/data/sites/command/{{$server->forge_id}}/{{$website->id}}/${commandId}`,
                type: 'GET',
                success: function (responseData) {
                    // Update the card content with the loaded data
                    if (typeof responseData[0] !== 'undefined') {
                        jQuery('.live-console').html(responseData[0].output);
                    }
                    console.log(responseData[0].status);
                    if (responseData[0].status !== "running") {
                        console.log(clearInterval(intervalId)); // Clear the interval when data.status is not "running"
                    }
                },
                error: function () {
                    // Handle AJAX errors if needed
                }
            });
        }
    
        function executeCommand() {
            var commandInput = jQuery('#command').val();
            if (commandInput) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('projects.command', ['server' => $server->forge_id, 'site' => $website->id]) }}",
                    data: {
                        command: commandInput,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function (responseData) {
                        // Update data with the received response
                        data = responseData;
    
                        jQuery('#command').val('');
                        intervalId = setInterval(() => {
                            getLastConsole(data.id);
                        }, 2000);
                    },
                    error: function () {
                        // Handle AJAX errors if needed
                    }
                });
            }
        }
    </script>
    
@endisset
@endisset
{{-- resources/views/users/index.blade.php --}}

@include('delete')



</body>
</html>
