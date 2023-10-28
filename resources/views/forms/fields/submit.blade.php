@isset($form->submit)
<button 
    type="submit" 
    id="@isset($form->submit->id){{$form->submit->id}} @endisset"
    class="btn my-2 @isset($form->submit->class){{$form->submit->class}} @else btn-primary  @endisset">
    @isset($form->submit->text){{$form->submit->text}} @else Submit @endisset
</button>
   


@else
<button type="submit" class="btn btn-primary my-2">Submit</button>
@endisset
