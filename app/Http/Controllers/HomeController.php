<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

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
        $user = Auth::user();

        switch ($user->role) {
            case 'customer': // Widok zwykłego usera
                $tickets = Ticket::where('owner_id', $user->id)->get();
                return view('home',['tickets' => $tickets]);
            case 'worker':
                $tickets = Ticket::where('worker_id', $user->id)->get();
                return view('home',['tickets' => $tickets]);
            case 'admin':
                $tickets = Ticket::where('worker_id', null)->get();
                return view('home',['tickets' => $tickets]);
        }

        return view('home');
    }
}
