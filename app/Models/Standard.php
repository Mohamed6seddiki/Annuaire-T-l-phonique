<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $fillable = [
        'numero',
        'nom',
        'id_direction',
        'id_sdirection',
        'id_departement',
        'service',
        'id_site',
        'niveau',
        'type',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'id_direction');
    }

    public function sdirection()
    {
        return $this->belongsTo(Sdirection::class, 'id_sdirection');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'id_site');
    }
}
