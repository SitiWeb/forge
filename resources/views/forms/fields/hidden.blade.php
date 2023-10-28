<input 
type="hidden" 
class="form-control @isset($row->id){{$row->id}}@endisset" 
id="@isset($row->id){{$row->id}} @else{{$row->name}}@endisset" 
name="{{$row->name}}" 
value="@isset($row->value){{$row->value}}@endisset" 
required>