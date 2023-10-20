<div class="p-3 bg-white rounded shadow"> 
    <h2>repository</h2>
    <table class="table bg-white">
       
        <thead>
            <tr>
                <th>Repository</th>
                <th>repositoryProvider</th>
                <th>repositoryBranch</th>
                <th>repositoryStatus</th>
                <th>deploymentStatus</th>
             
                {{-- Add other table headings as needed --}}
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{ $website->repository }}</td>
                    <td>{{ $website->repositoryProvider }}</td>
                    <td>{{ $website->repositoryBranch }}</td> 
                    <td>{{ $website->repositoryStatus }}</td>        
                    <td>{{ $website->deploymentStatus }}</td>      
                </tr>
        </tbody>
    </table>
</div>