<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Ticket;
use App\Models\TicketComment;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function deleteComment(TicketComment $comment)
    {
        $comment->delete();
        return redirect()->route('tickets.show', $comment->ticket_id);
    }

    public function addComment(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);
        $ticket->comments()->create([
            'comment' => $validated['comment'],
            'date' => now(),
            'author_id' => auth()->id(),
            'ticket_id' => $validated['ticket_id']
        ]);

        return redirect()->route('tickets.show', $validated['ticket_id']);
    }

    public function show($id)
    {
        $ticket = Ticket::with(['category', 'priority', 'status', 'owner', 'worker'])->findOrFail($id);
        $comments = $ticket->comments()->orderBy('created_at', 'desc')->get();
        return view('tickets.show', compact('ticket', 'comments'));
    }

    public function create()
    {
        $categories = \App\Models\TicketCategory::all();
        $priorities = \App\Models\TicketPriority::all();
        $statuses = \App\Models\TicketStatus::all();

        return view('tickets.create', compact('categories', 'priorities', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
            'deadline' => 'required|date|after:now',
        ]);

        $ticket = \App\Models\Ticket::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'ticket_category_id' => $validated['ticket_category_id'],
            'ticket_priority_id' => $validated['ticket_priority_id'],
            'ticket_status_id' => $validated['ticket_status_id'],
            'deadline' => $validated['deadline'],
            'date' => now(),
            'owner_id' => auth()->id(),
            'worker_id' => null,
        ]);

        // Wysyłamy maila do admina
        $user = \App\Models\User::where('role', 'admin')->first();
        Mail::to($user->email)->send(new \App\Mail\NewTicketMail($ticket));

        return redirect()->route('home');
    }

    public function edit(\App\Models\Ticket $ticket)
    {
        $categories = \App\Models\TicketCategory::all();
        $priorities = \App\Models\TicketPriority::all();
        $statuses = \App\Models\TicketStatus::all();

        return view('tickets.edit', compact('ticket', 'categories', 'priorities', 'statuses'));
    }

    public function update(Request $request, \App\Models\Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
            'deadline' => 'required|date|after:now',
        ]);

        $ticket->update($validated);
        return redirect()->route('tickets.show',$ticket->id);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('home')->with('success', 'Ticket deleted successfully.');
    }
}
?>