<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #888; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .header-table td { border: none; padding: 2px 0; }
    </style>
</head>
<body>
    <h2>LAPORAN ABSENSI</h2>
    <table class="header-table">
        <tr><td><b>{{ $ownerInfo }}</b></td></tr>
        <tr><td>Periode: {{ $periode }}</td></tr>
        <tr><td>Dicetak: {{ $printedAt }}</td></tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Status</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
        @foreach($attendances as $att)
            <tr>
                <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                <td>{{ $att->user->name }}</td>
                <td>{{ $att->user->email }}</td>
                <td>{{ $att->check_in ?? '-' }}</td>
                <td>{{ $att->check_out ?? '-' }}</td>
                <td>{{ ucfirst($att->status) }}</td>
                <td>{{ $att->work_hours ? number_format($att->work_hours, 2) : '0' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
