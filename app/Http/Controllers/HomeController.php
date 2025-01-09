<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;

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
            case 'admin':
                $query->where('worker_id', null);
                break;
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

        return view('home', [
            'tickets' => $tickets,
            'categories' => $categories,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'workers' => $workers
        ]);
    }
}
