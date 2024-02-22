<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Result; 

class ResultController extends Controller
{
    //

    public function show(Result $result){


        return view('results.show', ['result' => $result]); 


    }
}
