
<table class="table">
       <thead>
           <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Size</th>
               <th>Region</th>
               <th>IP Address</th>
               <th>Private IP Address</th>
               <th>PHP Version</th>
               <th>Created At</th>
               {{-- Add other table headings as needed --}}
           </tr>
       </thead>
       <tbody>

               <tr>
                   <td>{{ $server->id }}</td>
                   <td>{{ $server->name }}</td>
                   <td>{{ $server->size }}</td>
                   <td>{{ $server->region }}</td>
                   <td>{{ $server->ip_address }}</td>
                   <td>{{ $server->private_ip_address }}</td>
                   <td>{{ $server->php_version }}</td>
                   <td>{{ $server->server_created_at }}</td>
                   {{-- Add other table cells as needed --}}
               </tr>

       </tbody>
   </table>