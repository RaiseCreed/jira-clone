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
        $tickets = Ticket::where('owner_id', $user->id)->get();

        switch ($user->role) {
            case 'customer': // Widok zwykłego usera
                return view('home',['tickets' => $tickets]);
            case 'worker':
                $view = 'view2';
                break;
            case 'admin':
                $view = 'view3';
                break;
        }

        return view('home');
    }
}
