
<table class="table">
    <thead>
        <tr>
            <th>
                Cron job
            </th>
            <th>
                Time
            </th>
            <th>
                user
            </th>
        </tr>
    </thead>
    <tbody>
@foreach ($crons as $cron)
    <tr>
        <td>
            {{$cron->command}}
        </td>
        <td>
            {{$cron->cron}}
        </td>
        <td>
            {{$cron->user}}
        </td>
    </tr>
@endforeach

    </tbody>
</table>