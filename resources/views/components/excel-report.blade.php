<!DOCTYPE html>
<html>

<head>
    <title>Sales Reports</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>SND</th>
                <th>Nama Customer</th>
                <th>Waktu Visit</th>
                <th>Nama Sales</th>
                <th>VOC & Kendala</th>
                <th>Follow Up</th>
                <th>Evidence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->snd }}</td>
                    <td>{{ $report->alls->nama ?? '' }}</td>
                    <td>{{ $report->waktu_visit }}</td>
                    <td>{{ $report->user->name ?? '' }}</td>
                    <td>{{ $report->vockendals->voc_kendala ?? '' }}</td>
                    <td>{{ $report->follow_up }}</td>
                    <td>{{ $report->evidence }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
