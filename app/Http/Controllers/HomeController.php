<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;        
use App\Models\TicketComment; 
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;

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
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::query();

        switch ($user->role) {
            case 'customer': 
                $query->where('owner_id', $user->id);
                break;
            case 'worker':
                $query->where('worker_id', $user->id);
                break;
        }

        if ($user->role === 'admin' && !$request->has('worker')) {
            $request->merge(['worker' => 'unassigned']);
        }

        if ($request->has('title') && $request->title != '') {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('ticket_category_id', $request->category);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('ticket_priority_id', $request->priority);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('ticket_status_id', $request->status);
        }

        if ($request->has('deadline') && $request->deadline != '') {
            $query->whereDate('deadline', $request->deadline);
        }

        if ($request->has('worker') && $request->worker != '') {
            if($request->worker == 'unassigned') {
                $query->whereNull('worker_id');
            } else {
                $query->where('worker_id', $request->worker);
            }
        }
        

        $tickets = $query->paginate(3);
        $categories = TicketCategory::all();
        $priorities = TicketPriority::all();
        $statuses = TicketStatus::all();
        $workers = User::where('role','worker')->get();
        $totalTickets = Ticket::count();

        $workloads = Ticket::selectRaw('worker_id, COUNT(*) as workload')
            ->whereNotNull('worker_id')
            ->groupBy('worker_id')
            ->pluck('workload', 'worker_id');
        
        $workers = $workers->map(function ($worker) use ($workloads, $totalTickets) {
            $workloadCount = $workloads->get($worker->id, 0); 
                $worker->workload_percentage = $totalTickets > 0 
                ? round(($workloadCount / $totalTickets) * 100, 2) 
                : 0; 
            return $worker;
        });

        $quote = self::getQuote();
        return view('home', [
            'tickets' => $tickets,
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'workers' => $workers,
            'quote' => $quote,
        ]);
    }
  
    private static function getQuote()
    {
        $apikey = env('API_KEY');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.api-ninjas.com/v1/quotes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "X-Api-Key: $apikey"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response[0];
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