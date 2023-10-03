<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\RouteLogging;
use Input;

class RouteLoggingController extends Controller
{
    public function index() {
        
        $query = RouteLogging::query();

        $search = Input::input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->sortable();
                $q->where('username', "like", "%{$search}%");
                $q->orWhere('url', 'like', "%{$search}%");
                $q->orWhere('ip', 'like', "%{$search}%");
            });
        }

        $logging = $query
        ->sortable()
        ->orderBy('created_at', 'DESC')
        ->paginate(1000);

        if ($search) {
            $logging->appends(['search' => $search]);
        }

        return view('route_logging.index', compact('logging'));

    }
}
