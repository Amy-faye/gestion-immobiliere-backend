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
            padding: 40px;
            line-height: 1.6;
        }
        .doc-header {
            text-align: center;
            margin-bottom: 8px;
        }
        .doc-header h1 {
            font-family: 'DejaVu Serif', serif;
            font-size: 18px;
            color: #1B3B2F;
            margin: 0 0 6px;
            text-transform: uppercase;
        }
        .doc-header .subtitle {
            font-size: 10px;
            color: #8C8175;
        }
        .doc-header .ref {
            font-size: 9px;
            color: #A39B8B;
            margin-top: 3px;
        }
        hr {
            border: none;
            border-top: 2px solid #A8462B;
            margin: 18px 0 22px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #A8462B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 22px 0 10px;
            padding-bottom: 4px;
            border-bottom: 1px solid #EFE9DA;
        }
        .parties {
            display: table;
            width: 100%;
        }
        .party-box {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            border: 1px solid #D8CFBB;
            border-radius: 6px;
            padding: 12px 16px;
        }
        .party-spacer {
            display: table-cell;
            width: 4%;
        }
        .party-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #A8462B;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .party-name {
            font-weight: bold;
            font-size: 13px;
            color: #1B3B2F;
        }
        .party-detail {
            font-size: 11px;
            color: #6B6259;
            margin-top: 2px;
        }
        ul.info-list {
            margin: 0;
            padding-left: 18px;
        }
        ul.info-list li {
            margin-bottom: 5px;
        }
        .financial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .financial-table td {
            padding: 10px 14px;
            border: 1px solid #EFE9DA;
        }
        .financial-table td.label {
            background: #F5EFE3;
            font-weight: bold;
            width: 45%;
        }
        .financial-table td.value {
            text-align: right;
            font-weight: bold;
            color: #A8462B;
        }
        .clause {
            font-size: 10.5px;
            color: #4A453C;
            text-align: justify;
            margin-bottom: 8px;
        }
        .signature-block {
            display: table;
            width: 100%;
            margin-top: 45px;
        }
        .signature-col {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }
        .signature-spacer {
            display: table-cell;
            width: 4%;
        }
        .signature-label {
            font-size: 10px;
            color: #8C8175;
            margin-bottom: 40px;
        }
        .signature-name {
            font-size: 11px;
            font-weight: bold;
            border-top: 1px solid #24211C;
            padding-top: 4px;
            display: inline-block;
        }
        .legal-footer {
            margin-top: 40px;
            font-size: 8px;
            color: #A39B8B;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="doc-header">
    <h1>Contrat de bail à usage d'habitation</h1>
    <div class="subtitle">Établi entre le bailleur et le locataire désignés ci-dessous</div>
    <div class="ref">Contrat n° {{ str_pad($contrat->id, 6, '0', STR_PAD_LEFT) }} — Holding Baobab SA</div>
</div>
<hr>

<div class="section-title">I. Désignation des parties</div>
<div class="parties">
    <div class="party-box">
        <div class="party-label">Le Bailleur</div>
        <div class="party-name">{{ $contrat->bien->proprietaire->name ?? 'Propriétaire' }}</div>
        <div class="party-detail">{{ $contrat->bien->proprietaire->email ?? '' }}</div>
        <div class="party-detail">Représenté par : {{ $contrat->bien->gestionnaire->name ?? 'Holding Baobab SA' }} (gestionnaire)</div>
    </div>
    <div class="party-spacer"></div>
    <div class="party-box">
        <div class="party-label">Le Locataire</div>
        <div class="party-name">{{ $contrat->locataire->name }}</div>
        <div class="party-detail">{{ $contrat->locataire->email }}</div>
    </div>
</div>

<div class="section-title">II. Objet du contrat</div>
<p class="clause">Le présent contrat a pour objet la location du logement ci-après désigné, à usage exclusif d'habitation principale du locataire.</p>
<ul class="info-list">
    <li><strong>Type de bien :</strong> {{ $contrat->bien->type }}</li>
    <li><strong>Adresse :</strong> {{ $contrat->bien->adresse }}</li>
    @if($contrat->bien->description)
        <li><strong>Description :</strong> {{ $contrat->bien->description }}</li>
    @endif
</ul>

<div class="section-title">III. Durée du contrat</div>
<ul class="info-list">
    <li><strong>Date de prise d'effet :</strong> {{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</li>
    <li><strong>Date d'échéance :</strong> {{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}</li>
    <li><strong>Statut actuel :</strong> {{ ucfirst($contrat->statut) }}</li>
</ul>

<div class="section-title">IV. Conditions financières</div>
<table class="financial-table">
    <tr>
        <td class="label">Loyer mensuel</td>
        <td class="value">{{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} FCFA</td>
    </tr>
    <tr>
        <td class="label">Dépôt de garantie (caution)</td>
        <td class="value">{{ number_format($contrat->caution, 0, ',', ' ') }} FCFA</td>
    </tr>
</table>
<p class="clause" style="margin-top: 10px;">
    Le loyer est payable mensuellement, selon les modalités convenues entre les parties.
    La caution est restituée au locataire en fin de bail, déduction faite des sommes dues au titre de dégradations locatives éventuelles, dans les conditions prévues par la réglementation en vigueur.
</p>

<div class="section-title">V. Obligations des parties</div>
<p class="clause"><strong>Le bailleur s'engage à</strong> délivrer un logement décent, assurer la jouissance paisible du logement, et entretenir les locaux en état de servir à l'usage prévu.</p>
<p class="clause"><strong>Le locataire s'engage à</strong> payer le loyer aux échéances convenues, user paisiblement des locaux, répondre des dégradations survenues pendant la durée du contrat, et souscrire une assurance habitation.</p>

<div class="signature-block">
    <div class="signature-col">
        <div class="signature-label">Le Bailleur (ou son représentant)</div>
        <div class="signature-name">{{ $contrat->bien->gestionnaire->name ?? 'Holding Baobab SA' }}</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <div class="signature-label">Le Locataire</div>
        <div class="signature-name">{{ $contrat->locataire->name }}</div>
    </div>
</div>

<div class="legal-footer">
    Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }} — Holding Baobab SA — Système de Gestion Immobilière
</div>

</body>
</html>
