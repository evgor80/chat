<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * Return Vue SPA
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('spa');
    }
}