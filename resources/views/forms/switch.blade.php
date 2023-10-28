@switch($row->type)
    @case('text')
        @include('forms.fields.text')
        @break
    @case('select')
        @include('forms.fields.select')
        @break
    @case('hidden')
        @include('forms.fields.hidden')
        @break
        
    @default
        
@endswitch