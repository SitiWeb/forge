@switch($badge_value)
    @case('installed')
        @php
            $badge_type = 'success';
        @endphp
        @break
    @case('finished')
        @php
            $badge_type = 'success';
        @endphp
        @break
    @case('failed')
        @php
            $badge_type = 'danger';
        @endphp
        @break
    @default
        @php
            $badge_type = 'dark';
        @endphp
@endswitch

<span class="badge bg-{{$badge_type}}">{{ucfirst($badge_value)}}</span>