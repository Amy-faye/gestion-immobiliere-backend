<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #24211C;
            margin: 0;
            padding: 35px;
        }
        .parties {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .party-box {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            border: 1px solid #D8CFBB;
            border-radius: 6px;
            padding: 10px 14px;
        }
        .party-box + .party-box {
            padding-left: 14px;
        }
        .party-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #A8462B;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .party-name {
            font-weight: bold;
            font-size: 12px;
        }
        .party-detail {
            font-size: 11px;
            color: #6B6259;
        }
        .spacer-cell {
            display: table-cell;
            width: 4%;
        }

        .doc-title {
            text-align: center;
            margin: 25px 0 5px;
        }
        .doc-title h1 {
            font-family: 'DejaVu Serif', serif;
            font-size: 20px;
            color: #1B3B2F;
            margin: 0;
        }
        .doc-title .period {
            font-size: 14px;
            font-weight: bold;
            color: #A8462B;
            margin-top: 2px;
        }
        .doc-number {
            text-align: center;
            font-size: 10px;
            color: #8C8175;
            margin-bottom: 20px;
        }

        .content-box {
            border: 1px solid #D8CFBB;
            border-radius: 8px;
            padding: 20px 24px;
        }
        .content-box p {
            margin: 0 0 10px;
            line-height: 1.6;
        }
        .content-box strong {
            color: #1B3B2F;
        }

        .detail-block {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #EFE9DA;
        }
        .detail-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8C8175;
            margin-bottom: 8px;
        }
        .detail-line {
            display: table;
            width: 100%;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .detail-line .label {
            display: table-cell;
        }
        .detail-line .value {
            display: table-cell;
            text-align: right;
        }
        .detail-line.total .label,
        .detail-line.total .value {
            font-weight: bold;
            font-size: 13px;
            color: #1B3B2F;
            padding-top: 8px;
            border-top: 1px solid #D8CFBB;
        }
        .detail-line.balance .value {
            color: #2c7a52;
            font-weight: bold;
        }

        .signature-block {
            margin-top: 30px;
            text-align: right;
        }
        .signature-place {
            font-size: 11px;
            color: #6B6259;
            margin-bottom: 4px;
        }
        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #1B3B2F;
        }
        .signature-cursive {
            font-family: 'DejaVu Serif', serif;
            font-style: italic;
            font-size: 20px;
            color: #1B3B2F;
            margin-top: 6px;
        }

        .legal-footer {
            margin-top: 35px;
            font-size: 8px;
            color: #A39B8B;
            line-height: 1.5;
            text-align: justify;
        }
    </style>
</head>
<body>

<div class="parties">
    <div class="party-box">
        <div class="party-label">Bailleur</div>
        <div class="party-name">{{ $paiement->contrat->bien->proprietaire->name ?? 'Propriétaire' }}</div>
        <div class="party-detail">{{ $paiement->contrat->bien->proprietaire->email ?? '' }}</div>
    </div>
    <div class="spacer-cell"></div>
    <div class="party-box">
        <div class="party-label">Locataire destinataire</div>
        <div class="party-name">{{ $paiement->locataire->name }}</div>
        <div class="party-detail">{{ $paiement->locataire->email }}</div>
    </div>
</div>

<div class="doc-title">
    <h1>Quittance de loyer</h1>
    <div class="period">{{ \Carbon\Carbon::parse($paiement->date_paiement)->translatedFormat('F Y') }}</div>
</div>
<div class="doc-number">Quittance n° {{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</div>

<div class="content-box">
    <p><strong>Reçu de :</strong> {{ $paiement->locataire->name }}</p>
    <p><strong>La somme de :</strong> {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
    <p><strong>Le :</strong> {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
    <p><strong>Pour loyer et accessoires des locaux sis à :</strong><br>
        {{ $paiement->contrat->bien->adresse }} ({{ $paiement->contrat->bien->type }})
    </p>
    <p><strong>En paiement de la période du</strong>
        {{ \Carbon\Carbon::parse($paiement->date_paiement)->startOfMonth()->format('d/m/Y') }}
        <strong>au</strong>
        {{ \Carbon\Carbon::parse($paiement->date_paiement)->endOfMonth()->format('d/m/Y') }}
    </p>

    <div class="detail-block">
        <div class="detail-title">Détail</div>
        <div class="detail-line">
            <span class="label">Loyer mensuel</span>
            <span class="value">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="detail-line total">
            <span class="label">Montant total</span>
            <span class="value">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="detail-line">
            <span class="label">Paiement locataire ({{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }})</span>
            <span class="value">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="detail-line balance">
            <span class="label">Solde à payer</span>
            <span class="value">0 FCFA</span>
        </div>
    </div>
</div>

<div class="signature-block">
    <div class="signature-place">
        Fait à Dakar, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </div>
    <div class="signature-name">
        {{ $paiement->contrat->bien->gestionnaire->name ?? 'Holding Baobab SA' }}
    </div>
    <div class="signature-cursive">Baobab Immo</div>
</div>

<div class="legal-footer">
    La présente quittance annule tous les reçus qui auraient pu être établis précédemment en cas de paiement partiel de la période concernée.
    Ce document est à conserver par le locataire sans limitation de durée et peut être exigé en cas de litige relatif au paiement du loyer.
    Holding Baobab SA — Système de Gestion Immobilière.
</div>

</body>
</html>
