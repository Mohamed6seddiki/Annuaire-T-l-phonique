<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annuaire Téléphonique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page {
            margin: 12mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 20px 30px;
            color: #000;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 8px;
        }
        .header img {
            height: 50px;
            width: auto;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #000;
        }
        .info {
            display: table;
            width: 100%;
            font-size: 12px;
            color: #333;
            margin: 24px 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #999;
        }
        .info .left,
        .info .right {
            display: table-cell;
            white-space: nowrap;
        }
        .info .right {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        thead th,
        th {
            background: #e5e7eb !important;
            padding: 7px 10px;
            text-align: left;
            border: 1px solid #999;
            font-weight: 700;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        td {
            padding: 6px 10px;
            border: 1px solid #999;
            color: #000;
            background: #fff;
        }
        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>
    
        <div class="header">
            <img src="{{ $logoSrc ?? public_path('Radio-dz.png') }}" alt="Logo" style="height:50px;width:auto;">
            <h1>Radio Algérienne</h1>
        </div>

    <div class="info">
        <span class="left">Nombre d'éléments : {{ count($employees) }}</span>
        <span class="right">Date d'impression : {{ now()->format('d/m/Y') }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Nom</th>
                <th>Direction</th>
                <th>Sous-direction</th>
                <th>Département</th>
                <th>Service</th>
                <th>Site</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $e)
            <tr>
                <td>{{ $e['numero'] }}</td>
                <td>{{ $e['nom'] }}</td>
                <td>{{ $e['direction'] }}</td>
                <td>{{ $e['sous_direction'] }}</td>
                <td>{{ $e['departement'] }}</td>
                <td>{{ $e['service'] }}</td>
                <td>{{ $e['site'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:20px;font-size:13px;color:#666;">Aucun élément à afficher</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if (request()->has('print'))
    <script>
        window.onload = function () {
            window.print();
            setTimeout(function () { window.close(); }, 500);
        };
    </script>
    @endif
</body>
</html>