<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $fillable = ['libelle', 'libelle_arb'];

    public function standards()
    {
        return $this->hasMany(Standard::class, 'id_departement');
    }
}
