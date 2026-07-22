<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BienImmobilier extends Model
{
    protected $table = 'biens_immobiliers';

    protected $fillable = [
        'type',
        'adresse',
        'description',
        'loyer_mensuel',
        'statut',
        'photo',
        'proprietaire_id',
        'gestionnaire_id',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function contrats()
    {
        return $this->hasMany(ContratBail::class, 'bien_id');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'bien_id');
    }
}
