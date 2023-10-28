@php
$currentWidth = 0;
$submit = false;
@endphp

@include('forms.start')
@foreach ($form->fields as $row)
    
    @php
    if ($row->type == 'submit'){
        $submit = true;
    }
    $before = false;
    if ($currentWidth == 0 && isset($row->width) && $row->width != '-'){
        $before = true;
    }
    if (isset($row->width) && $row->width != '-'){
        $currentWidth = $currentWidth + $row->width;
    }
    @endphp
    @include('forms.before-field')
    @include('forms.switch')

    @php 
    $after = false;
    if (isset($row->width) && ($row->width) === '-'){
        $after = true;
    }
    if ($currentWidth >= 12 && isset($row->width)){
        $after = true;
        $currentWidth = 0;
    }
    elseif($currentWidth > 0 && isset($row->width)){
       // $after = true;
    }
    @endphp
    @include('forms.after-field')
@endforeach
@if ($submit === false)
    @include('forms.fields.submit')
@endif
@include('forms.end')