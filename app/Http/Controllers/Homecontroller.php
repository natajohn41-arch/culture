<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Homecontroller extends Controller
{
    function edit ($id) {
        return view('langues.edit', compact('id'));
    }
}


