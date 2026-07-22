<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratBail extends Model
{
    protected $table = 'contrats_bail';

    protected $fillable = [
        'date_debut',
        'date_fin',
        'loyer_mensuel',
        'caution',
        'statut',
        'fichier_pdf',
        'bien_id',
        'locataire_id',
    ];

    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }

    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'contrat_id');
    }
}
