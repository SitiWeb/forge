
<label for="{{$row->name}}">{{$row->label}}:</label>
<input 
    type="text" 
    class="form-control @isset($row->id){{$row->id}}@endisset" 
    id="@isset($row->id){{$row->id}} @else{{$row->name}}@endisset" 
    name="{{$row->name}}" 
    @isset($row->required) @if($row->required) required @endif @endisset
    @isset($row->value)value="{{$row->value}}"@endisset
    >
