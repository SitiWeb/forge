<li class="nav-item py-1 px-2 rounded  @if(env('APP_URL').$item->url == request()->url()) active shadow-sm @endif">
    <a class="sw-nav-item w-100 d-block"
       href="{{ env('APP_URL') }}{{ $item->url }}">
       {{ $item->title }}
    </a>
</li>
