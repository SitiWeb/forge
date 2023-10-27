<div class=""> 
  
    <table class="table bg-white">
 
        <thead>
            <tr>
                <th>Directory</th>
                <th>status</th>
                <th>aliases</th>
                <th>IsSecured?</th>
   
             
                {{-- Add other table headings as needed --}}
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{ $website->directory }}</td>
                    <td>{{ $website->status }}</td>
                    <td>{{ implode(', ',$website->aliases) }}</td> 
                   
                    <td>@if($website->isSecured)Yes @else No @endif</td>      
                </tr>
        </tbody>
    </table>
</div>