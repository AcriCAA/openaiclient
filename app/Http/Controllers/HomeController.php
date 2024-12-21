<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Result; 

use App\Models\Image; 


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $results = Result::all();

        return view('home', compact('results'));
    }


   
}
