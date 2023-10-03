<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class AdminstrationController extends Controller
{
    public function index() {

        return view('administration.index');
        
    }
}
