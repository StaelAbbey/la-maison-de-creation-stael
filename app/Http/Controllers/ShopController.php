<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class ShopController extends Controller
{
    public function index()
    {
        $produits = Produit::orderBy('created_at', 'DESC')->paginate(12);
        return view('shop', compact('produits'));
    }
}
