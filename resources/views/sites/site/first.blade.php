<div class="p-3 bg-white rounded shadow"> 
    <h2>algemeen</h2>
    <table class="table bg-white">
        
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>status</th>
                <th>username</th>
                <th>PHP Version</th>
             
                {{-- Add other table headings as needed --}}
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{ $website->id }}</td>
                    @if($website->isSecured)
                        <td><a target="_blank" href="https://{{ $website->name }}">{{ $website->name }}</a></td>
                    @else
                        <td><a target="_blank" href="http://{{ $website->name }}">{{ $website->name }}</a></td>
                    @endif

                    <td>{{ $website->status }}</td> 
                    <td>{{ $website->username }}</td>        
                    <td>{{ $website->phpVersion }}</td>      
                </tr>
        </tbody>
    </table>
</div>