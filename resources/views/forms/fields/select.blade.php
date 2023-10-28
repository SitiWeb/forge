
<label for="{{$row->name}}">{{$row->label}}</label>
<select 
    class="form-control" 
    id="@isset($row->id){{$row->id}} @else{{$row->name}}@endisset" 
    name="{{$row->name}}" 
    @isset($row->required) @if($row->required) required @endif @endisset
    >
    @isset($row->default_option)<option value="{{ $row->default_option['value'] }}">{{ $row->default_option['label'] }}</option>@endisset
    @foreach ($row->options as $key => $data)
       
        <option value="{{ $data['value'] }}"  @isset($data['value']) @if($row->selected == $data['value']) selected @endif @endisset >{{ $data['label'] }}</option>
    @endforeach
</select>
           