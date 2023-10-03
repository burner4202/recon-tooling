<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use thiagoalessio\TesseractOCR\TesseractOCR;

class CitadelTimerParseController extends Controller
{
	public function parse() {

		echo('<h2>Uploaded Image & Automatic Parsing and Data Entry.</h2><br>');
		echo('<h3>Image</h3>');
		echo('<img class="img-circle" src="https://i.imgur.com/4Du47pE.png"></img><p>');
		echo('<h2>Parsed Text ready for Database Entry</h2><br>');

		echo (new TesseractOCR('storage/citadels/vulnerability.png'))
		->run();


	}
}
