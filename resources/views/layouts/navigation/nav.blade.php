<ul class="navbar-nav me-auto">
    @foreach($navItems as $item)
        @include('layouts.navigation.nav-item')
    @endforeach
</ul>