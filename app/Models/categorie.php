<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categorie extends Model
{
    use HasFactory;
    public function produits()
    {
        return $this->hasMany(produit::class);
    }
}
