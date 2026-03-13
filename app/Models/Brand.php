<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Brand extends Model
{
     use HasFactory;
    public function produits()
    {
        return $this->hasMany(produit::class);
    }
}
