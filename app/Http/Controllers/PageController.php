<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Langue;
use App\Models\Contenu;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('public.about', [
            'totalRegions' => Region::count(),
            'totalLangues' => Langue::count(),
            'totalContenus' => Contenu::where('statut', 'valide')->count(),
            'totalUtilisateurs' => Utilisateur::count(),
        ]);
    }
}