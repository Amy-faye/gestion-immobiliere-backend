<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenances';

    protected $fillable = [
        'description_travaux',
        'cout',
        'date_intervention',
        'facture_pdf',
        'statut',
        'reclamation_id',
    ];

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class, 'reclamation_id');
    }
}
