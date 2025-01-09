<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;        
use App\Models\TicketComment;  // Używaj poprawnego modelu

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
                return view('home-customer',['tickets' => $tickets]);
            case 'worker':
                $tickets = Ticket::where('worker_id', $user->id)->get();
                return view('home-worker',['tickets' => $tickets]);
            case 'admin':
                $tickets = Ticket::where('worker_id', $user->id)->get(); // TODO
                return view('home-admin',['tickets' => $tickets]);
        }

        return view('home'); 
    }
    public function getDashboardData()
    {
        $user = Auth::user();

        // Filtr ticketów według użytkownika
        $query = Ticket::query();
        if ($user->role === 'customer') {
            $query->where('owner_id', $user->id);
        } elseif ($user->role === 'worker') {
            $query->where('worker_id', $user->id);
        }

        $ticketsByPriority = $query->selectRaw('ticket_priority_id, COUNT(*) as count')
            ->groupBy('ticket_priority_id')
            ->pluck('count', 'ticket_priority_id');
        $ticketsByStatus = $query->selectRaw('ticket_status_id, COUNT(*) as count')
            ->groupBy('ticket_status_id')
            ->pluck('count', 'ticket_status_id');

        $totalTickets = $query->count();

        return response()->json([
            'total_tickets' => $totalTickets,
            'tickets_by_priority' => $ticketsByPriority,
            'tickets_by_status' => $ticketsByStatus,
        ]);
    }
    public static function addMockData()
    {
        // Tworzenie przykładowych użytkowników
        $customer = User::create([
            'name' => 'Jan Kowalski',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $worker = User::create([
            'name' => 'Anna Nowak',
            'email' => 'worker@example.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
        ]);

        $ticket = Ticket::create([
            'title' => 'Problem z logowaniem',
            'description' => 'Nie mogę zalogować się do systemu.',
            'user_id' => $customer->id,
        ]);

        TicketComment::create([ 
            'content' => 'Proszę sprawdzić dane logowania.',
            'ticket_id' => $ticket->id,
            'user_id' => $worker->id,
        ]);

        TicketComment::create([ 
            'content' => 'Problem rozwiązany.',
            'ticket_id' => $ticket->id,
            'user_id' => $customer->id,
        ]);
    }
    public static function removeMockData()
    {
        Ticket::where('title', 'Problem z logowaniem')->delete();
        User::whereIn('email', ['customer@example.com', 'worker@example.com'])->delete();
        TicketComment::where('content', 'Proszę sprawdzić dane logowania.')
            ->orWhere('content','Problem rozwiązany.')
            ->delete();
    }
}