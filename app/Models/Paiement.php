<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'montant',
        'date_paiement',
        'mode_paiement',
        'statut',
        'quittance_pdf',
        'contrat_id',
        'locataire_id',
    ];

    public function contrat()
    {
        return $this->belongsTo(ContratBail::class, 'contrat_id');
    }

    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }
}
