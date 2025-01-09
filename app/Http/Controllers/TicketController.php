<?php

namespace App\Http\Controllers;


use App\Models\Ticket;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function index()
    {
        $tickets = Ticket::with(['category', 'priority', 'status', 'owner', 'worker'])->get();
        return view('tickets.index', compact('tickets'));
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
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048'
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

      
        // BartekChanges
        // if ($request->hasFile('attachment')) {
        //     $file = $request->file('attachment');
        //     $path = $file->store('attachments', 'public');

        //     $ticket->attachments()->create([
        //         'file_path' => $path,
        //         'file_name' => $file->getClientOriginalName(),
        //     ]);
        // }
      
        // Wysyłamy maila do admina
        $user = \App\Models\User::where('role', 'admin')->first();
        if($user) 
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

    // Aktualizacja ticketa
    public function update(Request $request, \App\Models\Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,docx|max:2048',
            'assigned_to' => 'nullable|exists:users,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'ticket_priority_id' => 'required|exists:ticket_priorities,id',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
            'deadline' => 'required|date|after:now',
        ]);


        if (auth()->user()->is_admin && isset($validated['assigned_to'])) {
            $ticket->worker_id = $validated['assigned_to'];
        }

        $ticket->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($request->hasFile('attachment')) {
            foreach ($ticket->attachments as $attachment) {
                Storage::delete('public/' . $attachment->file_path);
                $attachment->delete();
            }

            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $ticket->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }
      
        $ticket->update($validated);
      
     
        return redirect()->route('tickets.show',$ticket->id);
    }
  
    // Usuwanie ticketa
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        foreach ($ticket->attachments as $attachment) {
            Storage::delete('public/' . $attachment->file_path);
            $attachment->delete();
        }
        $ticket->delete();
        return redirect()->route('tickets.show')->with('success', 'Ticket został usunięty.');
    }

    // Dodawanie załącznika do istniejącego ticketa
    public function addAttachment(Request $request, $ticketId)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        $ticket->attachments()->create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'Załącznik został dodany.');
    }

    // Usuwanie załącznika
    public function destroyAttachment($ticketId, $attachmentId)
    {
        $attachment = Attachment::findOrFail($attachmentId);
        Storage::delete('public/' . $attachment->file_path);
        $attachment->delete();


         return back()->with('success', 'Załącznik został usunięty.');
    }
?>
