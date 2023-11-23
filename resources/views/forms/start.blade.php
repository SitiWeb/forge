<form action="{{$form->getAction()}}" method="@if ('PUT' == $form->getMethod()){{ 'POST' }}@else{{$form->getMethod()}}@endif">
    @if ($form->getMethod() == 'PUT')
        @method('PUT')
    @endif
    @csrf
