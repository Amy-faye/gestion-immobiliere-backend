<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    protected $table = 'reclamations';

    protected $fillable = [
        'type_incident',
        'description',
        'date_declaration',
        'statut',
        'locataire_id',
        'bien_id',
    ];

    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    public function bien()
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_id');
    }

    public function maintenance()
    {
        return $this->hasOne(Maintenance::class, 'reclamation_id');
    }
}
