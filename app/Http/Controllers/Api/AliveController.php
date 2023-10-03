<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class AliveController extends Controller
{

	public function alive() {

		return 'Alive';
		
	}
}
