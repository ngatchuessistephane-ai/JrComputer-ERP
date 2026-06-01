<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket SAV - {{ $ticket->ticket_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a7a3c; }
        .header h1 { color: #1a7a3c; margin: 0; }
        .info-box { margin-bottom: 20px; }
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { width: 150px; font-weight: bold; color: #1a7a3c; }
        .info-value { flex: 1; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #e5e7eb; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        .signature { margin-top: 30px; text-align: center; }
        .signature img { max-width: 200px; border: 1px solid #ccc; }
        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jr Computer Sarl</h1>
        <p>Service Après-Vente - Rapport d'intervention</p>
    </div>

    <div class="info-box">
        <div class="info-row"><div class="info-label">Ticket N°</div><div class="info-value">{{ $ticket->ticket_number }}</div></div>
        <div class="info-row"><div class="info-label">Client</div><div class="info-value">{{ $ticket->customer->name }}</div></div>
        <div class="info-row"><div class="info-label">Téléphone</div><div class="info-value">{{ $ticket->customer->phone ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Appareil</div><div class="info-value">{{ $ticket->device_model ?? $ticket->product?->name ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Numéro de série</div><div class="info-value">{{ $ticket->serial_number ?? '—' }}</div></div>
        <div class="info-row"><div class="info-label">Garantie</div><div class="info-value">{{ $ticket->is_warranty ? 'Sous garantie' : 'Hors garantie' }}</div></div>
        <div class="info-row"><div class="info-label">Date création</div><div class="info-value">{{ $ticket->created_at->format('d/m/Y H:i') }}</div></div>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Description de la panne :</strong><br>
        {{ $ticket->description_failure }}
    </div>

    @if($ticket->items->count())
    <table>
        <thead><tr><th>Pièce</th><th>Qté</th><th>Prix unitaire</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($ticket->items as $item)
            <tr>
                <td>{{ $item->sparePart->name }}</td>
                <td style="text-align:center">{{ $item->quantity }}</td>
                <td style="text-align:right">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                <td style="text-align:right">{{ number_format($item->quantity * $item->unit_price, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($ticket->intervention)
    <div style="margin: 15px 0;">
        <strong>Rapport technique :</strong><br>
        {{ $ticket->intervention->technical_report }}
    </div>
    <div class="info-row"><div class="info-label">Durée</div><div class="info-value">{{ $ticket->intervention->duration_minutes ?? '—' }} minutes</div></div>
    @endif

    @if($ticket->intervention?->client_signature)
    <div class="signature">
        <strong>Signature du client :</strong><br>
        <img src="{{ Storage::url($ticket->intervention->client_signature) }}" alt="Signature">
    </div>
    @endif

    <div class="footer">
        Document généré le {{ $generated_at }} - JR Computer Sarl
    </div>
</body>
</html>